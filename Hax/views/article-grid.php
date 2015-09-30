<li class="grid__item row listing">
  <?php $authors = get_coauthors($post->ID); ?>
  <div id="block">
    <?php echo get_avatar($authors[0], 72) ?>
  </div>
  <div class="block block--1">
    <h3 class="post__title heading">
      <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </h3>
    <div class="post__meta">
    </div>
  </div>
</li>
