<?php
/**
 * 導入事例 詳細テンプレート
 */
get_header();
?>

<main class="l-main case-archive"> <!-- help-archive と同じ構造 -->
  <div class="l-inner">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('case-article'); ?>>

        <!-- ▼ アイキャッチ -->
        <div class="thumb mb-6">
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large'); ?>
          <?php else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icatch.png" alt="導入事例のサムネイル">
          <?php endif; ?>
        </div>

        <!-- ▼ タイトル -->
        <header class="page-header mb-4">
          <h1 class="page-title"><?php the_title(); ?></h1>
          <p class="page-description text-sm text-gray-600">
            公開日: <?php echo get_the_date(); ?>
            <?php if (get_the_modified_date() !== get_the_date()) : ?>
              ／ 更新日: <?php echo get_the_modified_date(); ?>
            <?php endif; ?>
          </p>
        </header>

        <!-- ▼ 本文 -->
        <div class="article-content">
          <?php the_content(); ?>
        </div>

        <!-- ▼ 一覧に戻る -->
        <div class="back-to-archive mt-12 text-center">
          <a href="<?php echo get_post_type_archive_link('case'); ?>"
             class="back-to-archive-link inline-block px-6 py-3 rounded transition">
             導入事例一覧に戻る
          </a>
        </div>

      </article>

    <?php endwhile; else : ?>
      <p>該当する導入事例が見つかりません。</p>
    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
