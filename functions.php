<?php
/**
 * Theme Functions
 * event-reception テーマ機能設定
 */

// ログ
error_log('✅ functions.php loaded');

// ============================================
// inc ディレクトリ内のファイルを一括ロード
// ============================================

$inc_dir = get_template_directory() . '/inc/';

if (is_dir($inc_dir)) {

    // ファイル一覧取得
    $inc_files = glob($inc_dir . '*.php');

    if (!empty($inc_files)) {

        error_log('🧩 Found inc files: ' . print_r($inc_files, true));

        foreach ($inc_files as $file) {
            error_log('➡ loading: ' . basename($file));
            require_once $file;
        }

    } else {
        error_log('❌ No PHP files found in /inc/');
    }

} else {
    error_log('❌ /inc directory not found!');
}

// ※ meta-case.php も /inc に置けば自動ロードされる
//    → 二重 require しないように削除済み



// ============================================
// ICO ファイルアップロード許可
// ============================================
function allow_ico_uploads($mimes) {
    $mimes['ico'] = 'image/x-icon';
    return $mimes;
}
add_filter('upload_mimes', 'allow_ico_uploads');

// ============================================
// favicon を wp_head の最後に強制出力（Chrome対策）
// ============================================
add_action('wp_head', function () {
    ?>
    <link rel="icon" type="image/png"
          href="<?php echo get_template_directory_uri(); ?>/assets/img/icon_3c.png?v=20251217">
    <?php
}, 999);

// ============================================
// 外部確認URLの閲覧制御（Public Post Preview方式）
// ============================================

/**
 * pre_get_posts: external_previewトークンがある場合、posts_resultsフィルターを登録
 * ※ Public Post Previewプラグインのshow_public_preview()を参考
 */
add_action('pre_get_posts', function ($query) {
    if (!$query->is_main_query()) {
        return;
    }

    // external_previewとpreviewパラメータをチェック
    $token = $_GET['external_preview'] ?? '';
    $preview = $_GET['preview'] ?? '';
    if (empty($token) || $preview !== 'true') {
        return;
    }

    $post_type = $query->get('post_type');
    if ($post_type !== 'case') {
        return;
    }

    // キャッシュ無効化とnoindex設定
    if (!headers_sent()) {
        nocache_headers();
        header('X-Robots-Tag: noindex');
    }

    // posts_resultsフィルターを登録
    add_filter('posts_results', 'case_external_preview_set_publish', 10, 2);
});

/**
 * posts_results: トークン検証してOKなら記事のステータスを一時的にpublishに変更
 * ※ Public Post Previewプラグインのset_post_to_publish()を参考
 */
function case_external_preview_set_publish($posts, $query) {
    // フィルターを削除（他のクエリに影響させない）
    remove_filter('posts_results', 'case_external_preview_set_publish', 10);

    if (empty($posts)) {
        return $posts;
    }

    $post = $posts[0];
    $post_id = (int) $post->ID;

    // 公開済みなら正規URLにリダイレクト
    if ($post->post_status === 'publish') {
        wp_safe_redirect(get_permalink($post_id), 301);
        exit;
    }

    // トークン検証
    $token   = $_GET['external_preview'] ?? '';
    $saved   = get_post_meta($post_id, '_external_preview_token', true);
    $expires = intval(get_post_meta($post_id, '_external_preview_expires', true));

    if (empty($token) || empty($saved)) {
        wp_die('プレビューリンクが無効です。', '閲覧不可', ['response' => 404]);
    }

    if (!hash_equals($saved, $token)) {
        wp_die('プレビューリンクが無効です。', '閲覧不可', ['response' => 403]);
    }

    if (time() >= $expires) {
        wp_die('このリンクは有効期限が切れています。', '閲覧不可', ['response' => 403]);
    }

    // ★ 重要：記事のステータスを一時的にpublishに変更
    $posts[0]->post_status = 'publish';

    // コメントとピンバックを無効化
    add_filter('comments_open', '__return_false');
    add_filter('pings_open', '__return_false');

    return $posts;
}