<?php
/**
 * Template Name: サンクスページ（お問い合わせ）
 * お問い合わせフォーム送信後のサンクスページ
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
      <h1 class="page-title">お問い合わせを受け付けました</h1>
    </header>

    <div class="thanks-content">
      <p class="thanks-message">
        この度は、お問い合わせいただき、誠にありがとうございます。<br>
        内容を確認の上、担当者より折り返しご連絡いたします。
      </p>

      <div class="thanks-notice">
        <p>
          ※ 2〜3営業日以内にご連絡がない場合は、お手数ですが<br>
          再度お問い合わせいただくか、お電話にてご連絡ください。
        </p>
      </div>

      <div class="thanks-action">
        <a href="<?php echo home_url('/'); ?>" class="c-button c-button--outline">トップページへ戻る</a>
      </div>
    </div>

  </div>
</main>

<?php get_footer(''); ?>
