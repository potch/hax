<li class="list-item row listing">
  <?php 
    $authors = get_coauthors($post->ID);
  ?>
  <a href="<?php echo get_author_posts_url($authors[0]->ID); ?>" title="<?php echo $authors[0]->display_name; ?>">
    <?php echo get_avatar($authors[0], 72) ?>
  </a>
  <?php 
    $author_names = [];
    foreach($authors as $author)
    {
      $author_names[] = $author->display_name;
    }
    $authors = implode($author_names, ',');
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
      &nbsp;by
      <abbr title="<?php echo $authors ?>">
        <?php echo $authors ?>
      </abbr>
    </div>
  </div>
</li>
