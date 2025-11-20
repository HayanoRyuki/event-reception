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
