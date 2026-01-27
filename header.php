<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Tag Manager -->
  <script>
    (function(w,d,s,l,i){
      w[l]=w[l]||[];
      w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
      var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),
          dl=l!='dataLayer'?'&l='+l:'';
      j.async=true;
      j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
      f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MM8R64TR');
  </script>
  <!-- End Google Tag Manager -->

  <title><?php bloginfo('name'); ?></title>
  <meta name="description" content="<?php bloginfo('description'); ?>" />

  <!-- OGP設定 -->
  <meta property="og:title" content="<?php bloginfo('name'); ?>" />
  <meta property="og:description" content="<?php bloginfo('description'); ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />
  <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/ogp.png" />

  <!-- Twitter card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/ogp.png" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Poppins:wght@700&family=Noto+Sans+JP:wght@400;700&family=Roboto:wght@400;700&display=swap"
    rel="stylesheet">

  <!-- favicon -->
  <link rel="icon" type="image/png" sizes="32x32"
        href="<?php echo get_template_directory_uri(); ?>/assets/img/icon_3c.png">
  <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo get_template_directory_uri(); ?>/assets/img/icon_3c.png">
  <link rel="apple-touch-icon"
        href="<?php echo get_template_directory_uri(); ?>/assets/img/icon_3c.png">
  <link rel="icon" sizes="192x192"
        href="<?php echo get_template_directory_uri(); ?>/assets/img/icon_3c.png">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript>
  <iframe
    src="https://www.googletagmanager.com/ns.html?id=GTM-MM8R64TR"
    height="0"
    width="0"
    style="display:none;visibility:hidden">
  </iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<?php wp_body_open(); ?>

<!-- header -->
<div class="l-header__overlay"></div>
<header class="l-header">
  <div class="l-header__inner">
    <a href="/" class="l-header__logo">
      <img
        class="l-header__logo-img"
        src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.webp"
        alt="招待レセプション"
        width="396"
        height="104">
    </a>

    <!-- ハンバーガーメニューボタン（モバイル用） -->
    <button class="l-header__hamburger" type="button" aria-label="メニューを開く" aria-expanded="false">
      <span class="l-header__hamburger-line"></span>
      <span class="l-header__hamburger-line"></span>
      <span class="l-header__hamburger-line"></span>
    </button>

    <nav class="l-header__nav">
      <?php if (is_front_page()) : ?>
        <!-- TOPページ用メニュー -->
        <ul class="l-header__nav-list">
          <li><a href="#problem">よくある課題</a></li>
          <li><a href="#flow">利用フロー</a></li>
          <li><a href="#use-case">ユースケース</a></li>
          <li><a href="#merit">導入メリット</a></li>
          <li><a href="#case-study">導入事例</a></li>
          <li><a href="#plan">料金プラン</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
        <div class="l-header__nav-cta">
          <a class="c-button__header--frame" href="/resource/document/">
            資料をダウンロード
          </a>
          <a class="c-button__header" href="https://app.receptionist.jp/sign_in">
            無料ではじめる
          </a>
        </div>
      <?php else : ?>
        <!-- 下層ページ用メニュー -->
        <ul class="l-header__nav-list">
          <li><a href="<?php echo esc_url(home_url('/')); ?>">TOP</a></li>
          <li><a href="<?php echo esc_url(home_url('/case/')); ?>">導入事例</a></li>
          <li><a href="<?php echo esc_url(home_url('/resource/document/')); ?>">資料請求</a></li>
          <li><a href="<?php echo esc_url(home_url('/help/')); ?>">ヘルプ一覧</a></li>
        </ul>
        <div class="l-header__nav-cta">
          <a class="c-button__header" href="https://app.receptionist.jp/sign_in">
            無料ではじめる
          </a>
        </div>
      <?php endif; ?>
    </nav>
  </div>
</header>
