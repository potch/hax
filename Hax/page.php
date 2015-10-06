<?php get_header(); ?>


<main id="content-main" class="section">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <header id="content-head">
      <h1 class="page-title"><?php the_title(); ?></h1>
      <?php if ( current_user_can( 'edit_page', $post->ID ) ) : ?><p class="edit"><?php edit_post_link('Edit Page', '', ''); ?></p><?php endif; ?>
    </header><!-- /#content-head -->
    <article class="post" id="post-<?php the_ID(); ?>" role="article">
      <?php the_content('Read more&hellip;'); ?>
    </article>

    <?php wp_link_pages(array('before' => '<p class="pages"><b>Pages:</b> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

  <?php endwhile; ?>

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

</main><!-- /#content-main -->

<?php get_footer(); ?>
