<?php
/**
 * Footer Template – unified with front-page
 */
?>

<!--l-footer-->
<footer class="l-footer js-in-view fade-in-up">
  <div class="l-inner l-footer__inner">
    <div class="l-footer__container">
      <div class="l-footer__contents">
        <div class="l-footer__body">
          <h2 class="l-footer__title">さっそくはじめましょう</h2>
          <p class="l-footer__text">
            現在、事前エントリーを受付しております。<br>
            事前エントリーにご登録いただいた方には、サービスリリースと同時にご案内をお送りいたします。<br>
            また、事前エントリーにご登録いただいた上で有償利用いただいた方には、「50名分の招待枠」を追加でご提供いたします！<br>
            イベント受付でお困りの方は、この機会に是非ご登録をお願いいたします。<br>
          </p>

          <div class="l-footer__links">
            <a href="/resource/document/" class="c-button__large">資料をダウンロード</a>
          </div>
        </div>

        <div class="l-footer__company">
          <ul class="l-footer__company-menu">
            <li><a href="https://receptionist.co.jp/about">運営会社情報</a></li>
            <li><a href="https://help.receptionist.jp/?help=402">個人情報保護方針</a></li>
            <li><a href="/help/helpcenter/">ヘルプセンター</a></li>
            <li><a href="/help/terms/">利用規約</a></li>
          </ul>

          <div class="l-footer__company-info">
            <div class="l-footer__company-logo">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-footer.webp" loading="lazy" alt="Receptionist" width="264" height="64">
            </div>
            <div class="l-footer__company-copyright">
              &copy; RECEPTIONIST, Inc.<span>All rights reserved.</span>
            </div>
          </div>

        </div>
      </div>

      <div class="l-footer__img">
        <picture>
          <source media="(min-width: 768px)" srcset="<?php echo get_template_directory_uri(); ?>/assets/img/img_footer--pc.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/img_footer--sp.webp" loading="lazy" alt="" width="686" height="496">
        </picture>
      </div>

    </div>
  </div>
</footer>
<!--/l-footer-->

<?php wp_footer(); ?>
</body>
</html>
