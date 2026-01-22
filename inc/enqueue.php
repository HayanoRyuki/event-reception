<?php
/**
 * enqueue.php
 * CSS・JS読み込み設定（シンプル版）
 */

function eventlp_enqueue_scripts() {
  $theme_dir = get_template_directory();
  $theme_uri = get_template_directory_uri();

  // バージョン取得関数
  $ver = function($file) use ($theme_dir) {
    $path = "{$theme_dir}/{$file}";
    return file_exists($path) ? filemtime($path) : '1.0';
  };

  // ======================
  // 共通CSS（全ページ）
  // ======================
  wp_enqueue_style('eventlp-common', "{$theme_uri}/assets/css/common.css", [], $ver('assets/css/common.css'));
  wp_enqueue_style('eventlp-header', "{$theme_uri}/assets/css/header.css", ['eventlp-common'], $ver('assets/css/header.css'));
  wp_enqueue_style('eventlp-footer', "{$theme_uri}/assets/css/footer.css", ['eventlp-common'], $ver('assets/css/footer.css'));

  // Swiper（共通）
  wp_enqueue_style('swiper-css', "{$theme_uri}/assets/lib/swiper-bundle.min.css", [], '8.4.4');

  // ======================
  // TOPページ（front-page.css）
  // ======================
  if ( is_front_page() || is_home() || is_page_template('front-page.php') ) {
    wp_enqueue_style(
      'eventlp-front',
      "{$theme_uri}/assets/css/front-page.css",
      ['eventlp-common', 'eventlp-header', 'eventlp-footer'],
      $ver('assets/css/front-page.css')
    );
  }

  // ======================
  // 固定ページ
  // ======================
  if ( is_page() && !is_front_page() && !is_home() ) {
    $path = "{$theme_dir}/assets/css/page.css";
    if (file_exists($path)) {
      wp_enqueue_style('eventlp-page', "{$theme_uri}/assets/css/page.css", ['eventlp-common'], $ver('assets/css/page.css'));
    }
  }

  // ======================
  // アーカイブ
  // ======================
  foreach (['help', 'resource', 'case'] as $type) {
    if ( is_post_type_archive($type) ) {
      $file = "assets/css/archive-{$type}.css";
      if (file_exists("{$theme_dir}/{$file}")) {
        wp_enqueue_style("eventlp-archive-{$type}", "{$theme_uri}/{$file}", ['eventlp-common'], $ver($file));
      }
    }
  }

  // ======================
  // シングル
  // ======================
  foreach (['help', 'resource', 'case'] as $type) {
    if ( is_singular($type) ) {
      $file = "assets/css/single-{$type}.css";
      if (file_exists("{$theme_dir}/{$file}")) {
        wp_enqueue_style("eventlp-single-{$type}", "{$theme_uri}/{$file}", ['eventlp-common'], $ver($file));
      }
    }
  }

  // ======================
  // JS
  // ======================
  wp_enqueue_script('eventlp-script', "{$theme_uri}/assets/js/script.js", ['jquery'], $ver('assets/js/script.js'), true);
  wp_enqueue_script('swiper-js', "{$theme_uri}/assets/lib/swiper-bundle.min.js", ['jquery'], '8.4.4', true);
}

add_action('wp_enqueue_scripts', 'eventlp_enqueue_scripts');
