<?php
/*
Template Name: Landing Front
*/

// TOPページ用CSSを確実に読み込む
add_action('wp_enqueue_scripts', function() {
  $theme_uri = get_template_directory_uri();
  $theme_dir = get_template_directory();
  $path = "{$theme_dir}/assets/css/front-page.css";
  $version = file_exists($path) ? filemtime($path) : '1.0';

  wp_enqueue_style(
    'eventlp-front',
    "{$theme_uri}/assets/css/front-page.css",
    ['eventlp-common', 'eventlp-header', 'eventlp-footer'],
    $version
  );
}, 20); // 優先度20で後から読み込む
?>

<?php get_header(); ?>

<main class="l-main">

<?php get_template_part('sections/fv'); ?>
<?php get_template_part('sections/about'); ?>
<?php get_template_part('sections/problem'); ?>
<?php get_template_part('sections/flow'); ?>
<?php get_template_part('sections/use-case'); ?>
<?php get_template_part('sections/merit'); ?>
<?php get_template_part('sections/case-study'); ?>
<?php get_template_part('sections/plan'); ?>
<?php get_template_part('sections/faq'); ?>

</main>

<?php get_footer(); ?>
