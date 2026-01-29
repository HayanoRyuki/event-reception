<?php
/**
 * reCAPTCHA v3 設定
 *
 * 使用方法：
 * 1. Google reCAPTCHA Admin Console でサイトを登録
 *    https://www.google.com/recaptcha/admin
 * 2. wp-config.php に以下を追加：
 *    define('RECAPTCHA_SITE_KEY', 'your_site_key');
 *    define('RECAPTCHA_SECRET_KEY', 'your_secret_key');
 */

// ============================================
// 設定値チェック
// ============================================
function recaptcha_is_configured() {
    return defined('RECAPTCHA_SITE_KEY') && defined('RECAPTCHA_SECRET_KEY')
        && !empty(RECAPTCHA_SITE_KEY) && !empty(RECAPTCHA_SECRET_KEY);
}

// ============================================
// reCAPTCHA v3 スクリプト登録
// ============================================
add_action('wp_enqueue_scripts', 'enqueue_recaptcha_scripts');

function enqueue_recaptcha_scripts() {
    // 設定がない場合はスキップ
    if (!recaptcha_is_configured()) {
        return;
    }

    // フォームがあるページのみで読み込み（最適化）
    // 全ページで読み込む場合はこの条件を削除
    if (!is_singular('resource') && !is_page('contact')) {
        return;
    }

    // reCAPTCHA v3 API
    wp_enqueue_script(
        'google-recaptcha',
        'https://www.google.com/recaptcha/api.js?render=' . RECAPTCHA_SITE_KEY,
        [],
        null,
        true
    );

    // サイトキーをJavaScriptに渡す
    wp_localize_script('form-handler', 'recaptchaConfig', [
        'siteKey' => RECAPTCHA_SITE_KEY,
        'enabled' => true,
    ]);
}

// ============================================
// reCAPTCHA トークン検証
// ============================================
function verify_recaptcha_token($token, $action = 'submit_form') {
    // 設定がない場合はスキップ（開発環境対応）
    if (!recaptcha_is_configured()) {
        error_log('reCAPTCHA: Not configured, skipping verification');
        return ['success' => true, 'score' => 1.0, 'skipped' => true];
    }

    if (empty($token)) {
        error_log('reCAPTCHA: Empty token received');
        return ['success' => false, 'error' => 'Token is empty'];
    }

    // Google reCAPTCHA API で検証
    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'body' => [
            'secret'   => RECAPTCHA_SECRET_KEY,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ],
        'timeout' => 10,
    ]);

    if (is_wp_error($response)) {
        error_log('reCAPTCHA API error: ' . $response->get_error_message());
        // APIエラー時は通過させる（フォーム送信をブロックしない）
        return ['success' => true, 'score' => 0.5, 'api_error' => true];
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!$body) {
        error_log('reCAPTCHA: Invalid API response');
        return ['success' => true, 'score' => 0.5, 'parse_error' => true];
    }

    // アクション名のチェック
    if (isset($body['action']) && $body['action'] !== $action) {
        error_log('reCAPTCHA: Action mismatch. Expected: ' . $action . ', Got: ' . $body['action']);
        return ['success' => false, 'error' => 'Action mismatch'];
    }

    // スコアチェック（0.5以上を通過、調整可能）
    $score = $body['score'] ?? 0;
    $threshold = apply_filters('recaptcha_score_threshold', 0.5);

    $result = [
        'success'   => ($body['success'] ?? false) && $score >= $threshold,
        'score'     => $score,
        'action'    => $body['action'] ?? '',
        'hostname'  => $body['hostname'] ?? '',
        'threshold' => $threshold,
    ];

    // ログ出力（デバッグ用）
    if (!$result['success']) {
        error_log('reCAPTCHA: Verification failed. Score: ' . $score . ', Threshold: ' . $threshold);
    }

    return $result;
}
