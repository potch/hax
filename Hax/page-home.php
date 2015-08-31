<?php get_header(); ?>

<?php fc_custom_loop('cat='.$featured_id.'&posts_per_page=1&template=home-head-featarticles'); ?>

<section class="recent content section">
  <h2 class="heading">Recent Articles</h2>
  <ul class="grid">
    <?php fc_custom_loop('template=article-grid'); ?>
  </ul>
</section>

<?php get_footer(); ?>
