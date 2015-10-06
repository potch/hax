<?php get_header();

// Fetch these IDs now because we'll be using them a lot.
$demo_id = get_cat_ID('Demo');
$featureddemo_id = get_cat_ID('Featured Demo');
$featured_id = get_cat_ID('Featured Article');
?>

<main id="content-main" class="section">
  <?php if (have_posts()) : ?>

  <?php while (have_posts()) : the_post(); ?>

    <header id="content-head">
      <h1 class="page-title"><?php the_title(); ?></h1>
      <?php if ( current_user_can( 'edit_page', $post->ID ) ) : ?><p class="edit"><?php edit_post_link('Edit Page', '', ''); ?></p><?php endif; ?>
      <div id="content-bar" class="ignore-me"></div>
    </header>

    <article class="post" id="post-<?php the_ID(); ?>">
      <?php the_content('Read more&hellip;'); ?>
      <?php wp_link_pages(array('before' => '<p class="pages"><b>Pages:</b> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
    </article>
    <?php endwhile; ?>

    <?php if (fc_show_posts_nav()) : ?>
      <?php if (function_exists('fc_pagination') ) : fc_pagination(); else: ?>
        <ul class="nav-paging">
          <?php if ( $paged < $wp_query->max_num_pages ) : ?><li class="prev"><?php next_posts_link('Previous'); ?></li><?php endif; ?>
          <?php if ( $paged > 1 ) : ?><li class="next"><?php previous_posts_link('Next'); ?></li><?php endif; ?>
        </ul>
      <?php endif; ?>
    <?php endif; ?>

    <ul id="authors" class="about-authors">
      <?php $authors = hacks_list_authors(); ?>
      <?php foreach($authors as $author): ?>
        <li class="author-listing listing vcard">
          <h2 class="fn">
            <?php echo $author->display_name; ?>
          </h2>
          <div class="post-count">
            <a class="url" href="<?php echo get_author_posts_url($author->ID); ?>">
              <?php echo $author->total_posts.' post'.($author->total_posts > 1 ? 's' : ''); ?>
            </a>
          </div>
          <?php echo get_avatar($author, 72) ?>
          <p class="desc"><?php echo $author->description; ?></p>
          <?php echo dw_get_author_meta($author->ID); ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php else : ?>

    <div id="fail">
      <h2>Sorry, we couldn&#8217;t find that</h2>
      <p>We looked everywhere, but we couldn&#8217;t find the page or file you were looking for. A few possible explanations:</p>
      <ul>
        <li>You may have followed an out-dated link or bookmark.</li>
        <li>If you entered the address by hand, you may have mistyped it.</li>
        <li>You might have just discovered an error. Congratulations!</li>
      </ul>
    </div>

  <?php endif; ?>

</main>

<?php get_footer(); ?>
