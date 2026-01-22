<?php
/**
 * ヘルプ記事 詳細テンプレート
 */
get_header();
?>

<main class="l-main help-single">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('help-article'); ?>>

      <!-- タイトル -->
      <header class="page-header">
        <h1 class="page-title"><?php the_title(); ?></h1>
        <p class="post-meta">
          公開日: <?php echo get_the_date(); ?>
          <?php if (get_the_modified_date() !== get_the_date()) : ?>
            ／ 更新日: <?php echo get_the_modified_date(); ?>
          <?php endif; ?>
        </p>
      </header>

      <!-- 本文 -->
      <div class="article-content">
        <?php the_content(); ?>
      </div>

      <!-- 一覧に戻る -->
      <div class="back-to-archive">
        <a href="<?php echo get_post_type_archive_link('help'); ?>">ヘルプ記事一覧に戻る</a>
      </div>

    </article>
  <?php endwhile; else : ?>
    <p>該当するヘルプ記事が見つかりません。</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
