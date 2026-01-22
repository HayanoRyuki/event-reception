<?php
/**
 * 導入事例：詳細テンプレート（2カラム版）
 */
get_header();
?>

<main class="l-main single-case">
  <div class="case-2col">

    <!-- ▼ 左：メインコラム -->
    <div class="case-main">

      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('case-article'); ?>>

          <!-- タイトル -->
          <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>

            <p class="page-description">
              公開日: <?php echo get_the_date('Y年n月j日'); ?>
            </p>
          </header>

          <!-- アイキャッチ -->
          <div class="case-thumb">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('large'); ?>
            <?php endif; ?>
          </div>

          <!-- 本文 -->
          <div class="article-content">
            <div class="case-container">
              <?php the_content(); ?>
            </div>
          </div>

          <!-- 一覧へ戻る -->
          <div class="back-to-archive">
            <a href="<?php echo get_post_type_archive_link('case'); ?>" class="back-to-archive-link">
              導入事例一覧に戻る
            </a>
          </div>

        </article>

      <?php endwhile; endif; ?>

    </div>

    <!-- ▼ 右：企業概要（メタボックス） -->
    <aside class="case-sidebar">

      <div class="case-sidebar-box">
        <?php
        // ロゴ画像
        $logo_id = get_post_meta(get_the_ID(), '_case_logo_image_id', true);
        if ($logo_id) :
          $logo_url = wp_get_attachment_image_url($logo_id, 'medium');
        ?>
          <div class="case-logo">
            <img src="<?php echo esc_url($logo_url); ?>" alt="企業ロゴ">
          </div>
        <?php endif; ?>

        <h3 class="sidebar-title">企業概要</h3>
        <dl class="case-info-list">
          <?php
          $fields = [
            '_case_company_name' => '企業名',
            '_case_industry'     => '業種',
            '_case_employee_size' => '従業員数',
          ];
          foreach ($fields as $key => $label) :
            $val = get_post_meta(get_the_ID(), $key, true);
            if ($val) :
          ?>
            <div class="case-info-item">
              <dt><?php echo esc_html($label); ?></dt>
              <dd><?php echo esc_html($val); ?></dd>
            </div>
          <?php
            endif;
          endforeach;
          ?>
        </dl>
      </div>

    </aside>

  </div>
</main>

<?php get_footer(); ?>
