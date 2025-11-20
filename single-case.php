<?php
/**
 * 導入事例：詳細テンプレート（single-case.php）
 */
get_header();
?>

<main class="l-main single-case">
  <div class="l-inner">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class('case-article'); ?>>

        <!-- ▼ アイキャッチ -->
        <div class="case-thumb mb-6">
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large'); ?>
          <?php else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icatch.png" alt="">
          <?php endif; ?>
        </div>

        <!-- ▼ タイトル -->
        <header class="page-header">
          <h1 class="page-title"><?php the_title(); ?></h1>

          <p class="page-description">
            公開日: <?php echo get_the_date(); ?>
            <?php if (get_the_modified_date() !== get_the_date()) : ?>
              ／ 更新日: <?php echo get_the_modified_date(); ?>
            <?php endif; ?>
          </p>
        </header>

        <!-- ▼ 本文（中央幅を完全制御） -->
        <div class="article-content">
          <div class="case-container">
            <?php the_content(); ?>
          </div>
        </div>

        <!-- ▼ 一覧へ戻る -->
        <div class="back-to-archive">
          <a href="<?php echo get_post_type_archive_link('case'); ?>"
             class="back-to-archive-link">
            導入事例一覧に戻る
          </a>
        </div>

      </article>

    <?php endwhile; endif; ?>

  </div>
</main>

<?php get_footer(); ?>
