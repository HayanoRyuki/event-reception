<?php
/**
 * 導入事例 一覧テンプレート
 */
get_header();
?>

<main class="l-main case-archive">
  <div class="l-inner">

    <header class="archive-header mb-8">
      <h1 class="archive-title">導入事例一覧</h1>
      <p class="archive-description text-gray-600 text-sm">
        「招待レセプション」をご利用いただいた企業様の事例をご紹介します。
      </p>
    </header>

    <?php if (have_posts()) : ?>
      <div class="archive-list grid grid-cols-1 md:grid-cols-2 gap-10">

        <?php while (have_posts()) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class('case-article'); ?>>

            <!-- ▼ アイキャッチ -->
            <div class="thumb mb-4">
              <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('large'); ?>
                <?php else : ?>
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icatch.png" alt="導入事例のサムネイル">
                <?php endif; ?>
              </a>
            </div>

            <!-- ▼ タイトル -->
            <h2 class="case-title text-lg font-bold mb-2">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>

            <!-- ▼ 抜粋 -->
            <p class="case-excerpt text-sm text-gray-600">
              <?php echo wp_trim_words(get_the_excerpt(), 32); ?>
            </p>

          </article>
        <?php endwhile; ?>

      </div>

      <!-- ▼ ページネーション -->
      <div class="pagination mt-12">
        <?php the_posts_pagination(); ?>
      </div>

    <?php else : ?>
      <p>導入事例が登録されていません。</p>
    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
