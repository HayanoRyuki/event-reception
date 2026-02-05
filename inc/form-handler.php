<?php
/**
 * フォーム送信処理
 * - Ajax処理
 * - Slack通知（wp-config.phpからWebhook URL取得）
 * - Pardot連携（フォームハンドラー）
 */

// ============================================
// 設定値（Pardot Endpoint）
// ※ Slack Webhook URLはwp-config.phpで定義
// ============================================
define('PARDOT_ENDPOINT_REQUEST', 'https://t.receptionist.jp/l/436112/2026-01-25/8m98g4'); // 資料請求用
define('PARDOT_ENDPOINT_CONTACT', 'https://t.receptionist.jp/l/436112/2026-01-26/8m98gm'); // お問い合わせ用

// ============================================
// Ajaxエンドポイント登録
// ============================================
add_action('wp_ajax_submit_form', 'handle_form_submission');
add_action('wp_ajax_nopriv_submit_form', 'handle_form_submission');

/**
 * フォーム送信処理（Ajax）
 */
function handle_form_submission() {
    // Nonceチェック
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'form_submit_nonce')) {
        wp_send_json_error(['message' => 'セキュリティエラー']);
        return;
    }

    // Honeypot チェック（ボットは隠しフィールドを自動入力する）
    $honeypot = sanitize_text_field($_POST['website_url'] ?? '');
    if (!empty($honeypot)) {
        error_log('Honeypot triggered: ' . $honeypot);
        // ボットには成功したように見せる（再試行防止）
        wp_send_json_success([
            'message'  => '送信が完了しました',
            'redirect' => home_url('/resource-thanks/'),
        ]);
        return;
    }

    // reCAPTCHA v3 検証
    if (function_exists('verify_recaptcha_token') && recaptcha_is_configured()) {
        $recaptcha_token = sanitize_text_field($_POST['recaptcha_token'] ?? '');
        $recaptcha_result = verify_recaptcha_token($recaptcha_token, 'submit_form');

        if (!$recaptcha_result['success']) {
            error_log('reCAPTCHA verification failed: ' . print_r($recaptcha_result, true));
            wp_send_json_error([
                'message' => 'スパム判定されました。時間をおいて再度お試しください。',
                'recaptcha' => $recaptcha_result,
            ]);
            return;
        }

        // スコアをログに記録（デバッグ用）
        error_log('reCAPTCHA score: ' . ($recaptcha_result['score'] ?? 'N/A'));
    }

    // フォームタイプ判定
    $form_type = sanitize_text_field($_POST['form_type'] ?? 'request');

    // フォームデータ収集
    $data = [
        'company'      => sanitize_text_field($_POST['company'] ?? ''),
        'department'   => sanitize_text_field($_POST['department'] ?? ''),
        'lastname'     => sanitize_text_field($_POST['lastname'] ?? ''),
        'firstname'    => sanitize_text_field($_POST['firstname'] ?? ''),
        'email'        => sanitize_email($_POST['email'] ?? ''),
        'tel'          => sanitize_text_field($_POST['tel'] ?? ''),
        'event_type'   => sanitize_text_field($_POST['event_type'] ?? ''),
        'event_timing' => sanitize_text_field($_POST['event_timing'] ?? ''),
        'event_size'   => sanitize_text_field($_POST['event_size'] ?? ''),
        'message'      => sanitize_textarea_field($_POST['message'] ?? ''),
        'page_title'   => sanitize_text_field($_POST['page_title'] ?? ''),
        'referer'      => esc_url_raw($_POST['referer'] ?? ''),
    ];

    // 必須項目チェック（共通：会社名、姓、名、メール）
    if (empty($data['company']) || empty($data['lastname']) || empty($data['firstname']) || empty($data['email'])) {
        wp_send_json_error(['message' => '必須項目を入力してください']);
        return;
    }

    // 資料請求フォームの場合：全項目必須
    if ($form_type === 'request') {
        if (empty($data['department']) || empty($data['tel']) || empty($data['event_type']) || empty($data['event_timing']) || empty($data['event_size'])) {
            wp_send_json_error(['message' => '必須項目を入力してください']);
            return;
        }
    }

    // 1. Slack通知
    $slack_result = send_slack_notification($form_type, $data);

    // 2. Pardot連携
    $pardot_result = send_to_pardot($form_type, $data);

    // サンクスページURL
    $thanks_url = ($form_type === 'contact')
        ? home_url('/contact-thanks/')
        : home_url('/resource-thanks/');

    wp_send_json_success([
        'message'     => '送信が完了しました',
        'redirect'    => $thanks_url,
        'slack'       => $slack_result,
        'pardot'      => $pardot_result,
    ]);
}

/**
 * Slack通知送信
 * ※ Webhook URLはwp-config.phpで定義（SLACK_WEBHOOK_REQUEST, SLACK_WEBHOOK_CONTACT）
 */
function send_slack_notification($form_type, $data) {
    // wp-config.phpからWebhook URL取得
    $webhook_url = '';
    if ($form_type === 'contact' && defined('SLACK_WEBHOOK_CONTACT')) {
        $webhook_url = SLACK_WEBHOOK_CONTACT;
    } elseif (defined('SLACK_WEBHOOK_REQUEST')) {
        $webhook_url = SLACK_WEBHOOK_REQUEST;
    }

    if (empty($webhook_url)) {
        error_log('Slack Webhook URL not configured in wp-config.php for: ' . $form_type);
        return ['success' => false, 'error' => 'Webhook URL not configured'];
    }

    // 通知タイトル
    $title = ($form_type === 'contact') ? ':email: お問い合わせ' : ':page_facing_up: 資料請求';

    // Slack Block Kit形式のメッセージ
    $blocks = [
        [
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => $title . ' - ' . $data['company'],
                'emoji' => true,
            ],
        ],
        [
            'type' => 'section',
            'fields' => [
                ['type' => 'mrkdwn', 'text' => "*会社名:*\n" . $data['company']],
                ['type' => 'mrkdwn', 'text' => "*部署:*\n" . ($data['department'] ?: '-')],
            ],
        ],
        [
            'type' => 'section',
            'fields' => [
                ['type' => 'mrkdwn', 'text' => "*お名前:*\n" . $data['lastname'] . ' ' . $data['firstname']],
                ['type' => 'mrkdwn', 'text' => "*メール:*\n" . $data['email']],
            ],
        ],
        [
            'type' => 'section',
            'fields' => [
                ['type' => 'mrkdwn', 'text' => "*電話番号:*\n" . ($data['tel'] ?: '-')],
                ['type' => 'mrkdwn', 'text' => "*イベント種類:*\n" . ($data['event_type'] ?: '-')],
            ],
        ],
        [
            'type' => 'section',
            'fields' => [
                ['type' => 'mrkdwn', 'text' => "*開催予定時期:*\n" . ($data['event_timing'] ?: '-')],
                ['type' => 'mrkdwn', 'text' => "*招待人数規模:*\n" . ($data['event_size'] ?: '-')],
            ],
        ],
    ];

    // お問い合わせ内容がある場合
    if (!empty($data['message'])) {
        $blocks[] = [
            'type' => 'section',
            'text' => ['type' => 'mrkdwn', 'text' => "*お問い合わせ内容:*\n" . $data['message']],
        ];
    }

    // タイムスタンプ
    $blocks[] = [
        'type' => 'context',
        'elements' => [
            ['type' => 'mrkdwn', 'text' => ':link: 送信元: ' . ($data['page_title'] ?: 'Unknown')],
            ['type' => 'mrkdwn', 'text' => ':clock1: ' . date('Y/m/d H:i:s')],
        ],
    ];

    $payload = [
        'blocks' => $blocks,
        'text'   => $title . ' - ' . $data['company'],
    ];

    // Slack APIへPOST
    $response = wp_remote_post($webhook_url, [
        'body'    => json_encode($payload),
        'headers' => ['Content-Type' => 'application/json'],
        'timeout' => 10,
    ]);

    if (is_wp_error($response)) {
        error_log('Slack notification error: ' . $response->get_error_message());
        return ['success' => false, 'error' => $response->get_error_message()];
    }

    $response_body = wp_remote_retrieve_body($response);
    if ($response_body !== 'ok') {
        error_log('Slack response: ' . $response_body);
        return ['success' => false, 'error' => $response_body];
    }

    return ['success' => true];
}

/**
 * Pardot連携（フォームハンドラー）
 */
function send_to_pardot($form_type, $data) {
    // Endpoint URL取得
    $endpoint = ($form_type === 'contact')
        ? PARDOT_ENDPOINT_CONTACT
        : PARDOT_ENDPOINT_REQUEST;

    if (empty($endpoint)) {
        error_log('Pardot endpoint not configured for: ' . $form_type);
        return ['success' => false, 'error' => 'Pardot endpoint not configured'];
    }

    // Pardotフィールドマッピング
    $pardot_data = [
        'company'      => $data['company'],
        'department'   => $data['department'],
        'last_name'    => $data['lastname'],
        'first_name'   => $data['firstname'],
        'email'        => $data['email'],
        'phone'        => $data['tel'],
        'event_type'   => $data['event_type'],
        'event_timing' => $data['event_timing'],
        'event_size'   => $data['event_size'],
        'comments'     => $data['message'],
    ];

    // PardotへPOST
    $response = wp_remote_post($endpoint, [
        'body'    => $pardot_data,
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        error_log('Pardot submission error: ' . $response->get_error_message());
        return ['success' => false, 'error' => $response->get_error_message()];
    }

    $response_code = wp_remote_retrieve_response_code($response);

    if ($response_code >= 200 && $response_code < 400) {
        return ['success' => true];
    }

    error_log('Pardot response code: ' . $response_code);
    return ['success' => false, 'error' => 'Response code: ' . $response_code];
}

// ============================================
// フロントエンド用スクリプト登録
// ============================================
add_action('wp_enqueue_scripts', 'enqueue_form_scripts');

function enqueue_form_scripts() {
    wp_enqueue_script(
        'form-handler',
        get_template_directory_uri() . '/assets/js/form-handler.js',
        ['jquery'],
        filemtime(get_template_directory() . '/assets/js/form-handler.js'),
        true
    );

    wp_localize_script('form-handler', 'formHandler', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('form_submit_nonce'),
    ]);
}
