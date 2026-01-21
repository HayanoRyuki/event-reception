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
// 外部確認URLの閲覧制御
// ============================================
add_action('template_redirect', function () {
    if (!is_singular('case')) return;

    global $post;
    if ($post->post_status === 'publish') return;

    $token   = $_GET['external_preview'] ?? '';
    $saved   = get_post_meta($post->ID, '_external_preview_token', true);
    $expires = intval(get_post_meta($post->ID, '_external_preview_expires', true));

    if ($token && $saved && hash_equals($saved, $token) && time() < $expires) {
        return; // 表示許可
    }

    wp_die('このページは確認期限が切れています。', '閲覧不可', ['response' => 403]);
});