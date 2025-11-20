<?php
/**
 * Archive Template for Resource
 */
get_header();
?>

<main class="l-main">
  <div class="l-inner">
    <h1 class="page-title">リソース一覧</h1>

    <?php if (have_posts()) : ?>
      <ul class="resource-archive">
        <?php while (have_posts()) : the_post(); ?>
          <li>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else : ?>
      <p>リソースが見つかりません。</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
