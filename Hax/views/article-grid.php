<li class="grid__item row listing">
  <?php $authors = get_coauthors($post->ID); ?>
  <div id="block">
    <?php echo get_avatar($authors[0], 72) ?>
  </div>
  <div class="block block-1">
    <h3 class="post__title heading">
      <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </h3>
    <p class="post__tease">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    <div class="post__meta">
    </div>
  </div>
</li>
