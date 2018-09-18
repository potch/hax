<li class="list-item row listing">
<?php
  if (function_exists('coauthors')) :
    $authors = get_coauthors($post->ID);
    echo get_avatar($authors[0]->user_email, 72);
  else :
    echo get_avatar(get_the_author_meta('user_email'), 72);
  endif;
?>
  <div class="block block--1">
    <h3 class="post__title">
      <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </h3>
    <p class="post__tease"><?php echo get_the_excerpt(); ?></p>
    <div class="post__meta">
      Posted on
      <abbr class="published" title="<?php the_time('Y-m-d\TH:i:sP'); ?>">
        <?php the_time(get_option('date_format')); ?>
      </abbr>
    </div>
  </div>
</li>
