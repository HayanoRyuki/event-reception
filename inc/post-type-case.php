<?php
/**
 * カスタム投稿タイプ：導入事例（case）
 */

error_log('📘 post-type-case.php loaded');

function create_case_post_type() {

  $labels = array(
    'name'               => '導入事例',
    'singular_name'      => '導入事例',
    'add_new'            => '新規追加',
    'add_new_item'       => '導入事例を追加',
    'edit_item'          => '導入事例を編集',
    'new_item'           => '新規導入事例',
    'view_item'          => '導入事例を見る',
    'search_items'       => '導入事例を検索',
    'not_found'          => '導入事例が見つかりません',
    'not_found_in_trash' => 'ゴミ箱に導入事例はありません',
  );

  $args = array(
    'labels'        => $labels,
    'public'        => true,
    'has_archive'   => true,
    'menu_position' => 5,
    'menu_icon'     => 'dashicons-welcome-write-blog',
    'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
    'rewrite'       => array('slug' => 'case'),
  );

  register_post_type('case', $args);
}
add_action('init', 'create_case_post_type');
