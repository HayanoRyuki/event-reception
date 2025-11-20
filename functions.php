<?php
/**
 * Theme Functions
 * eventLP テーマ機能設定
 */

// まず確実にログを出す
error_log('✅ functions.php loaded');

// テーマパスを固定的に定義（子テーマでも確実に動くように）
$theme_dir = __DIR__ . '/inc/';

// 読み込むファイル一覧を取得してログ出力
$files = glob($theme_dir . '*.php');
error_log('🧩 Found inc files: ' . print_r($files, true));

// ファイルが見つからない場合のために確認
if (empty($files)) {
  error_log('❌ No files found in /inc/');
}

// incフォルダのすべてのphpを安全に読み込み
foreach ($files as $file) {
  error_log('✅ loading: ' . basename($file));
  require_once $file;
}

function allow_ico_uploads($mimes) {
  $mimes['ico'] = 'image/x-icon';
  return $mimes;
}
add_filter('upload_mimes', 'allow_ico_uploads');