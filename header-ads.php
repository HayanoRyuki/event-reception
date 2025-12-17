<?php
/**
 * LP用ヘッダー（ロゴのみ・リンクなし）
 * 呼び出し方: get_header('ads');
 */
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-MM8R64TR');</script>
  <!-- End Google Tag Manager -->

  <!-- 検索にヒットしないようにするための記述です -->
  <meta name="robots" content="noindex" />
  <title><?php bloginfo('name'); ?></title>
  <meta name="description" content="<?php bloginfo('description'); ?>" />

  <!-- OGP設定 -->
  <meta property="og:title" content="<?php bloginfo('name'); ?>" />
  <meta property="og:description" content="<?php bloginfo('description'); ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />
  <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/img/ogp.png" />

  <?php wp_head(); ?>

  <!-- PC / ブラウザ用 favicon（明示指定） -->
<link rel="icon"
      type="image/png"
      sizes="32x32"
      href="<?php echo get_template_directory_uri(); ?>/assets/images/icon_3c.png">

<link rel="icon"
      type="image/png"
      sizes="16x16"
      href="<?php echo get_template_directory_uri(); ?>/assets/images/icon_3c.png">

<!-- iOS / Android 用アイコン -->
<link rel="apple-touch-icon"
      href="<?php echo get_template_directory_uri(); ?>/assets/images/icon_3c.png">

<link rel="icon"
      sizes="192x192"
      href="<?php echo get_template_directory_uri(); ?>/assets/images/icon_3c.png">

</head>

<body <?php body_class('lp-page'); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MM8R64TR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

  <?php wp_body_open(); ?>

  <header class="l-header l-header--lp">
    <div class="l-header__inner">
      <div class="l-header__logo">
        <img class="l-header__logo-img"
             src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.webp"
             alt="招待レセプション"
             width="396"
             height="104">
      </div>
    </div>
  </header>
