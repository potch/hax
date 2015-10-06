<li class="list-item row listing">
  <?php $authors = get_coauthors($post->ID); ?>
  <?php echo get_avatar($authors[0], 72) ?>
  <div class="block block--1">
    <h3 class="post__title">
      <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </h3>
    <p class="post__tease"><?php echo get_the_excerpt(); ?></p>
  </div>
</li>
