<?php
/**
 * Archive Template for Help
 */
get_header();
?>

<main class="l-main help-archive">

  <header class="page-header">
    <h1 class="page-title">ヘルプ一覧</h1>
    <p class="page-description">RECEPTIONISTシリーズ各製品のヘルプ記事をまとめています。</p>
  </header>

  <?php if (have_posts()) : ?>
    <ul class="help-list">
      <?php while (have_posts()) : the_post(); ?>
        <li class="help-item">
          <a href="<?php the_permalink(); ?>">
            <div class="thumb">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
              <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icatch.png" alt="ヘルプ記事のサムネイル">
              <?php endif; ?>
            </div>
            <div class="content">
              <h2><?php the_title(); ?></h2>
              <p class="excerpt"><?php echo wp_trim_words(get_the_excerpt(), 40, '...'); ?></p>
              <span class="readmore">続きを読む →</span>
            </div>
          </a>
        </li>
      <?php endwhile; ?>
    </ul>

    <div class="pagination">
      <?php the_posts_pagination([
        'mid_size' => 2,
        'prev_text' => '«',
        'next_text' => '»',
      ]); ?>
    </div>

  <?php else : ?>
    <p>ヘルプ記事が見つかりません。</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
