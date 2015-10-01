<?php get_header();

// Fetch these IDs now because we'll be using them a lot.
$demo_id = get_cat_ID('Demo');
$featureddemo_id = get_cat_ID('Featured Demo');
$featured_id = get_cat_ID('Featured Article');

$search_count = 0;
$search = new WP_Query("s=$s & showposts=-1");
if($search->have_posts()) : while($search->have_posts()) : $search->the_post();
$search_count++;
endwhile; endif;
?>

<!-- /#content-head -->

<main id="content-main" class="section">
  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
  <h1 class="page-title">
    <?php if (is_category($demo_id)) : ?>Demos
    <?php elseif (is_category()) : ?><?php single_cat_title(); ?> Articles
    <?php elseif (is_tag()) : ?>Articles tagged &#8220;<?php single_tag_title(); ?>&#8221;
    <?php elseif (is_day()) : ?>Articles for <?php the_time('F jS, Y'); ?>
    <?php elseif (is_month()) : ?>Articles for <?php the_time('F Y'); ?>
    <?php elseif (is_year()) : ?>Articles for <?php the_time('Y'); ?>
    <?php elseif (is_author()) : ?>Articles by <?php echo get_userdata(intval($author))->display_name; ?>
    <?php elseif (is_search()) : ?>Search results for &ldquo;<?php the_search_query(); ?>&rdquo; &#10025;
    <?php elseif (isset($_GET['paged']) && !empty($_GET['paged'])) : ?>Articles
    <?php else : ?>Articles
    <?php endif; ?>
  </h1>

  <?php if (have_posts()) : ?>
  <ul class="article-list">
    butts
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
</main><!-- /#content-main -->

<?php get_footer(); ?>
