<?php
// ======================================
// カスタム投稿タイプ：リソース
// ======================================
function eventlp_register_resource_post_type() {
  $labels = [
    'name'               => 'リソース',
    'singular_name'      => 'リソース',
    'add_new'            => '新規追加',
    'add_new_item'       => '新しいリソースを追加',
    'edit_item'          => 'リソースを編集',
    'new_item'           => '新しいリソース',
    'view_item'          => 'リソースを表示',
    'search_items'       => 'リソースを検索',
    'not_found'          => 'リソースが見つかりません',
    'not_found_in_trash' => 'ゴミ箱にリソースはありません',
    'menu_name'          => 'リソース',
  ];

  $args = [
    'labels'        => $labels,
    'public'        => true,
    'has_archive'   => true,
    'rewrite'       => ['slug' => 'resource'],
    'menu_position' => 5,
    'menu_icon'     => 'dashicons-media-document',
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
    'show_in_rest'  => true,
  ];

  register_post_type('resource', $args);
}
add_action('init', 'eventlp_register_resource_post_type');
