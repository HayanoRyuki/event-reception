<?php
// ======================================
// カスタム投稿タイプ：ヘルプ記事
// ======================================
function eventlp_register_help_post_type() {
  $labels = [
    'name'               => 'ヘルプ記事',
    'singular_name'      => 'ヘルプ記事',
    'menu_name'          => 'ヘルプ',
    'add_new'            => '新規追加',
    'add_new_item'       => '新しいヘルプ記事を追加',
    'edit_item'          => 'ヘルプ記事を編集',
    'view_item'          => 'ヘルプ記事を表示',
    'all_items'          => 'すべてのヘルプ記事',
    'search_items'       => 'ヘルプ記事を検索',
    'not_found'          => 'ヘルプ記事が見つかりません。',
  ];

  $args = [
    'labels'        => $labels,
    'public'        => true,
    'has_archive'   => true,
    'rewrite'       => ['slug' => 'help'],
    'menu_position' => 5,
    'menu_icon'     => 'dashicons-editor-help',
    'show_in_rest'  => true,
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
  ];

  register_post_type('help', $args);
}
add_action('init', 'eventlp_register_help_post_type');
