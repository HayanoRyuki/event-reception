<?php
/**
 * Template Name: サンクスページ（資料請求）
 * 資料請求フォーム送信後のサンクスページ
 */

wp_enqueue_style(
  'eventlp-page',
  get_template_directory_uri() . '/assets/css/page.css',
  [],
  filemtime(get_template_directory() . '/assets/css/page.css')
);

get_header('');
?>

<main class="l-main page page-thanks">
  <div class="container">

    <header class="page-header mb-12">
      <h1 class="page-title">資料請求を受け付けました</h1>
    </header>

    <div class="thanks-content">
      <p class="thanks-message">
        この度は、招待レセプションの資料をご請求いただき、誠にありがとうございます。<br>
        ご入力いただいたメールアドレス宛に、資料をお送りいたします。
      </p>

      <div class="thanks-notice">
        <p>
          ※ 数分経ってもメールが届かない場合は、迷惑メールフォルダをご確認いただくか、<br>
          お手数ですが再度お申し込みください。
        </p>
      </div>

      <div class="thanks-action">
        <a href="<?php echo home_url('/'); ?>" class="c-button c-button--outline">トップページへ戻る</a>
      </div>
    </div>

  </div>
</main>

<?php get_footer(''); ?>
