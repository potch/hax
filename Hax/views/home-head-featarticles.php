<?php
$cat_classes = '';
foreach((get_the_category()) as $category) {
    $cat_classes .= 'cat--' . $category->slug . ' ';
}
$postID = $post->ID;
?>
<section
  class="feature section section--fullwidth"
  <?php if ($feature_style): ?>
  style="<?php echo $feature_style; ?>"
  <?php endif; ?> >
  <div class="feature__pane">
    <h1><a class="feature__link" href="<?php the_permalink() ?>"><?php the_title(); ?>&nbsp;&rarr;</a></h1>
    <h2 class="feature__label">Featured Article</h2>
  </div>
</section>
