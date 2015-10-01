<?php get_header();

// Fetch these IDs now because we'll be using them a lot.
$demo_id = get_cat_ID('Demo');
$featureddemo_id = get_cat_ID('Featured Demo');
$featured_id = get_cat_ID('Featured Article');
?>

<main id="content-main" class="section">
  <?php if (is_category($featured_id)) : ?>
    <h1 class="page-title">Featured Articles</h1>
  <?php elseif (is_category($featureddemo_id)) : ?>
    <h1 class="page-title">Featured Demos</h1>
  <?php elseif (is_category()) : ?>
    <h1 class="page-title"><?php single_cat_title(); ?> Articles</h1>
  <?php else : ?>
    <h1 class="page-title">Articles</h1>
  <?php endif; ?>

  <?php if (have_posts()) : ?>
  <ul class="article-list">
    <?php fc_custom_loop($query_string.'&template=article-list'); ?>
  </ul>

  <hr class="dino">

  <?php if (fc_show_posts_nav()) : ?>
    <?php if (function_exists('fc_pagination') ) : fc_pagination(); else: ?>
      <nav class="nav-paging">
        <?php if ( $paged < $wp_query->max_num_pages ) : ?><span class="nav-paging__next"><?php next_posts_link('Next'); ?></span><?php endif; ?>
        <?php if ( $paged > 1 ) : ?><span class="nav-paging__prev"><?php previous_posts_link('Previous'); ?></span><?php endif; ?>
      </nav>
    <?php endif; ?>
  <?php endif; ?>

  <?php else : ?>
    <p class="fail">Sorry, there are no articles here.</p>
  <?php endif; ?>
</main><!-- /#content-main -->

<?php get_footer(); ?>
