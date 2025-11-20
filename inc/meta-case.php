<?php
/**
 * 導入事例：企業概要メタボックス
 */

function case_company_meta_box() {
  add_meta_box(
    'case_company_info',
    '企業概要',
    'case_company_meta_box_html',
    'case',
    'side',
    'high'
  );
}
add_action('add_meta_boxes', 'case_company_meta_box');

function case_company_meta_box_html($post) {
  $fields = [
    'company_name' => '企業名',
    'industry'     => '業種',
    'size'         => '従業員数',
    'location'     => '所在地',
    'plan'         => '導入プラン'
  ];

  echo '<div class="case-company-meta">';
  foreach ($fields as $key => $label) {
    $value = get_post_meta($post->ID, $key, true);
    echo "<p><label>{$label}<br>";
    echo "<input type='text' name='{$key}' value='".esc_attr($value)."' style='width:100%;' />";
    echo "</label></p>";
  }
  echo "</div>";
}

function case_company_meta_save($post_id) {
  $keys = ['company_name','industry','size','location','plan'];
  foreach ($keys as $key) {
    if (isset($_POST[$key])) {
      update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
    }
  }
}
add_action('save_post', 'case_company_meta_save');
