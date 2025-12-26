<!--p-case-study-->
<section class="p-case-study" id="case-study">
  <div class="l-inner">

    <div class="c-section-head">
      <hgroup class="c-section-head__title-wrap">
        <p class="c-section-head__sub">Case Study</p>
        <h2 class="c-section-head__title">導入事例</h2>
      </hgroup>

      <div class="c-section-head__cta">
        <a href="https://forms.gle/e4hNdW9Hors8DYQ16" class="c-button__large">
          事前エントリーに登録
        </a>
      </div>
    </div>

    <div class="p-case-study__container">

<?php
// ▼ 導入事例（case）から 1件取得
$case = get_posts([
  'post_type'      => 'case',
  'posts_per_page' => 1,
  'post_status'    => 'publish'
]);

if ($case) :
  $case_id = $case[0]->ID;

  // 企業ロゴ（single-case と同じメタキー）
  $logo_id  = get_post_meta($case_id, '_company_logo_id', true);
  $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
?>
      <!-- ================================
           ★ 表示する 1件目 ONLY（動的）
      ================================= -->
      <a href="<?php echo get_permalink($case_id); ?>"
         class="p-case-study__item">

        <div class="p-case-study__item-img img01">
          <?php
            if (has_post_thumbnail($case_id)) :
              echo get_the_post_thumbnail($case_id, 'large', [
                'loading' => 'lazy'
              ]);
            endif;
          ?>
        </div>

        <div class="p-case-study__item-body">

          <h3 class="p-case-study__item-title">
            <?php echo get_the_title($case_id); ?>
          </h3>

          <div class="p-case-study__item-company">
            <div class="p-case-study__item-logo">
              <?php if ($logo_url): ?>
                <img src="<?php echo esc_url($logo_url); ?>"
                     loading="lazy"
                     alt="">
              <?php else: ?>
                <!-- ★ ロゴ未登録時 fallback -->
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.webp"
                     loading="lazy"
                     alt="">
              <?php endif; ?>
            </div>
          </div>

        </div>
      </a>
<?php endif; ?>

      <!-- ================================
           ★ 将来追加予定：2件目
           必要になったらコメント解除！
      ================================= -->
      <!--
      <div class="p-case-study__item">
        <div class="p-case-study__item-img img02">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/img_case-study02.webp" loading="lazy" alt="">
        </div>
        <div class="p-case-study__item-body">
          <h3 class="p-case-study__item-title">
            （ここに2件目のタイトル）
          </h3>
          <div class="p-case-study__item-company">
            <div class="p-case-study__item-logo">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/img_case-study-logo02.webp" loading="lazy" alt="">
            </div>
          </div>
        </div>
      </div>
      -->

      <!-- ================================
           ★ 将来追加予定：3件目
      ================================= -->
      <!--
      <div class="p-case-study__item">
        <div class="p-case-study__item-img img03">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/img_case-study03.webp" loading="lazy" alt="">
        </div>
        <div class="p-case-study__item-body">
          <h3 class="p-case-study__item-title">
            （ここに3件目のタイトル）
          </h3>
          <div class="p-case-study__item-company">
            <div class="p-case-study__item-logo">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.webp" loading="lazy" alt="">
            </div>
          </div>
        </div>
      </div>
      -->

    </div>
  </div>
</section>
<!--/end p-case-study-->
