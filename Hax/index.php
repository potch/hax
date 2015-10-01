<?php
// Don't allow direct access to the theme
if(!defined('DB_NAME')) {
  exit('Direct template access is not allowed');
}

get_header();

// Fetch these IDs now because we'll be using them a lot.
$demo_id = get_cat_ID('Demo');
$featureddemo_id = get_cat_ID('Featured Demo');
$featured_id = get_cat_ID('Featured Article');
?>

<main id="content-main" class="section">
  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
  <h1 class="page-title">
    Articles
  </h1>

  <?php if (have_posts()) : ?>
  <ul class="article-list">
    <?php fc_custom_loop($query_string.'&template=article-list'); ?>
  </ul>

  <hr class="dino">

  <?php if (fc_show_posts_nav()) : ?>
    <?php if (function_exists('fc_pagination') ) : fc_pagination(); else: ?>
      <nav class="nav-paging">
        <?php if ( $paged < $wp_query->max_num_pages ) : ?><span class="nav-paging__next"><?php next_posts_link('Older'); ?></span><?php endif; ?>
        <?php if ( $paged > 1 ) : ?><span class="nav-paging__prev"><?php previous_posts_link('Newer'); ?></span><?php endif; ?>
      </nav>
    <?php endif; ?>
  <?php endif; ?>

  <?php else : ?>
    <p class="fail">Sorry, there are no articles here.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
