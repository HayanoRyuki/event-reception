<?php
// ======================================
// 基本設定
// ======================================
function eventlp_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'eventlp_setup');
