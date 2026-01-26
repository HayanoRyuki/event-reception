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

    <div class="p-case-study__container p-case-study__container--grid">

      <?php
      // カスタム投稿タイプ「case」から最新2件を取得
      $case_query = new WP_Query(array(
        'post_type'      => 'case',
        'posts_per_page' => 2,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
      ));

      $counter = 1;

      if ($case_query->have_posts()) :
        while ($case_query->have_posts()) : $case_query->the_post();
          $img_class = 'img0' . $counter;
          $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');

          // ロゴ画像を取得（メタフィールドから）
          $logo_id = get_post_meta(get_the_ID(), '_case_logo_image_id', true);
          $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
      ?>

      <a href="<?php the_permalink(); ?>" class="p-case-study__card">

        <div class="p-case-study__card-img <?php echo esc_attr($img_class); ?>">
          <?php if ($thumbnail) : ?>
          <img
            src="<?php echo esc_url($thumbnail); ?>"
            loading="lazy"
            alt="<?php the_title_attribute(); ?>">
          <?php else : ?>
          <img
            src="<?php echo get_template_directory_uri(); ?>/assets/img/img_case-study-placeholder.webp"
            loading="lazy"
            alt="<?php the_title_attribute(); ?>">
          <?php endif; ?>
        </div>

        <div class="p-case-study__card-body">

          <h3 class="p-case-study__card-title">
            <?php the_title(); ?>
          </h3>

          <div class="p-case-study__card-logo">
            <?php if ($logo_url) : ?>
            <img src="<?php echo esc_url($logo_url); ?>"
                 loading="lazy"
                 alt="<?php the_title_attribute(); ?>">
            <?php else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.webp"
                 loading="lazy"
                 alt="<?php the_title_attribute(); ?>">
            <?php endif; ?>
          </div>

        </div>
      </a>

      <?php
          $counter++;
        endwhile;
        wp_reset_postdata();
      endif;
      ?>

    </div>
  </div>
</section>
<!--/end p-case-study-->
