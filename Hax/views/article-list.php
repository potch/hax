<li class="list-item row listing">
  <?php $authors = get_coauthors($post->ID); ?>
  <div id="block">
    <?php echo get_avatar($authors[0], 72) ?>
  </div>
  <div class="block block--1">
    <h3 class="post__title">
      <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </h3>
    <p class="post__tease"><?php the_excerpt() ?></p>
    <!-- <div class="post__meta">
      <span class="entry-cat">
        <?php fc_category_minusfeatdemo(' '); ?>
      </span>
    </div> -->
  </div>
</li>
