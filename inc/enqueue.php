<?php
/**
 * enqueue.php
 * CSS・JS読み込み設定（style.css を使わない版）
 */

function eventlp_enqueue_scripts() {
  $theme_dir = get_template_directory();
  $theme_uri = get_template_directory_uri();

  // ======================
  // 共通関数
  // ======================
  $safe_version = function (string $abs_path) {
    return file_exists($abs_path) ? (filemtime($abs_path) ?: '1.0') : '1.0';
  };


  // ======================
  // 共通CSS（常時読み込み）
  // ======================

  // ★ common.css（全ページのベースCSS）
  $common = "{$theme_dir}/assets/css/common.css";
  if (file_exists($common)) {
    wp_enqueue_style(
      'eventlp-common',
      "{$theme_uri}/assets/css/common.css",
      [],
      $safe_version($common)
    );
  }

  // header / footer
  foreach (['header', 'footer'] as $part) {
    $path = "{$theme_dir}/assets/css/{$part}.css";
    if (file_exists($path)) {
      wp_enqueue_style(
        "eventlp-{$part}",
        "{$theme_uri}/assets/css/{$part}.css",
        ['eventlp-common'],
        $safe_version($path)
      );
    }
  }

  // Swiper CSS
  $swiper_css = "{$theme_dir}/assets/lib/swiper-bundle.min.css";
  if (file_exists($swiper_css)) {
    wp_enqueue_style(
      'swiper-css',
      "{$theme_uri}/assets/lib/swiper-bundle.min.css",
      [],
      '8.4.4'
    );
  }


  // ======================
  // ページ別CSS
  // ======================

  // Front Page
  if ( is_front_page() || is_page_template('front-page.php') ) {
    $path = "{$theme_dir}/assets/css/front-page.css";
    if (file_exists($path)) {
      wp_enqueue_style(
        'eventlp-front',
        "{$theme_uri}/assets/css/front-page.css",
        ['eventlp-common', 'eventlp-header', 'eventlp-footer', 'swiper-css'],
        $safe_version($path)
      );
    }
  }

  // 固定ページ
  if (is_page() && !is_front_page()) {
    $path = "{$theme_dir}/assets/css/page.css";
    if (file_exists($path)) {
      wp_enqueue_style(
        'eventlp-page',
        "{$theme_uri}/assets/css/page.css",
        ['eventlp-common', 'eventlp-header', 'eventlp-footer'],
        $safe_version($path)
      );
    }
  }

  // アーカイブ
  foreach (['help', 'resource'] as $type) {
    $path = "{$theme_dir}/assets/css/archive-{$type}.css";
    if (is_post_type_archive($type) && file_exists($path)) {
      wp_enqueue_style(
        "eventlp-archive-{$type}",
        "{$theme_uri}/assets/css/archive-{$type}.css",
        ['eventlp-common', 'eventlp-header', 'eventlp-footer'],
        $safe_version($path)
      );
    }
  }

  // シングル
  foreach (['help', 'resource'] as $type) {
    $path = "{$theme_dir}/assets/css/single-{$type}.css";
    if (is_singular($type) && file_exists($path)) {
      wp_enqueue_style(
        "eventlp-single-{$type}",
        "{$theme_uri}/assets/css/single-{$type}.css",
        ['eventlp-common', 'eventlp-header', 'eventlp-footer'],
        $safe_version($path)
      );
    }
  }

  // case single
  $case_single = "{$theme_dir}/assets/css/single-case.css";
  if (is_singular('case') && file_exists($case_single)) {
    wp_enqueue_style(
      'eventlp-single-case',
      "{$theme_uri}/assets/css/single-case.css",
      ['eventlp-common', 'eventlp-header', 'eventlp-footer'],
      $safe_version($case_single)
    );
  }

  // case archive
  $case_archive = "{$theme_dir}/assets/css/archive-case.css";
  if (is_post_type_archive('case') && file_exists($case_archive)) {
    wp_enqueue_style(
      'eventlp-archive-case',
      "{$theme_uri}/assets/css/archive-case.css",
      ['eventlp-common', 'eventlp-header', 'eventlp-footer'],
      $safe_version($case_archive)
    );
  }


  // ======================
  // JS
  // ======================

  // 共通 script
  $script = "{$theme_dir}/assets/js/script.js";
  if (file_exists($script)) {
    wp_enqueue_script(
      'eventlp-script',
      "{$theme_uri}/assets/js/script.js",
      ['jquery'],
      $safe_version($script),
      true
    );
  }

  // Swiper
  $swiper_js = "{$theme_dir}/assets/lib/swiper-bundle.min.js";
  if (file_exists($swiper_js)) {
    wp_enqueue_script(
      'swiper-js',
      "{$theme_uri}/assets/lib/swiper-bundle.min.js",
      ['jquery'],
      '8.4.4',
      true
    );
  }
}

add_action('wp_enqueue_scripts', 'eventlp_enqueue_scripts');
