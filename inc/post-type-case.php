<?php
// カスタム投稿タイプ：導入事例
function eventlp_register_case_study() {
  register_post_type(
    'case-study',
    array(
      'labels' => array(
        'name'          => '導入事例',
        'singular_name' => '導入事例',
      ),
      'public'        => true,
      'menu_position' => 5,
      'menu_icon'     => 'dashicons-awards',
      'has_archive'   => true,
      'rewrite'       => array('slug' => 'case'),
      'supports'      => array('title', 'editor', 'thumbnail', 'excerpt'),
    )
  );
}
add_action('init', 'eventlp_register_case_study');
