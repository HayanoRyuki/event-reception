<?php
/**
 * 導入事例 一覧
 */
get_header();
?>

<main class="l-main case-archive">
  <div class="l-inner">

    <header class="archive-header">
      <h1 class="archive-title">導入事例一覧</h1>
      <p class="archive-description">
        「招待レセプション」をご利用いただいた企業様の事例をご紹介します。
      </p>
    </header>

    <?php if (have_posts()) : ?>
      <div class="archive-list">

        <?php while (have_posts()) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class('case-article'); ?>>

            <!-- アイキャッチ -->
            <a href="<?php the_permalink(); ?>" class="thumb">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
              <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/img_case-study-placeholder.webp" alt="<?php the_title_attribute(); ?>">
              <?php endif; ?>
            </a>

            <!-- カード本文 -->
            <div class="card-body">
              <h2 class="case-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>

              <p class="case-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 60, '...'); ?>
              </p>

              <a href="<?php the_permalink(); ?>" class="case-read-more">
                この記事を読む
              </a>
            </div>

          </article>
        <?php endwhile; ?>

      </div>

      <div class="pagination">
        <?php the_posts_pagination(); ?>
      </div>

    <?php else : ?>
      <p>導入事例が登録されていません。</p>
    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
