<?php
$cat_classes = '';
foreach((get_the_category()) as $category) {
    $cat_classes .= 'cat--' . $category->slug . ' ';
}
?>
<section class="feature section section--fullwidth <?php echo $cat_classes; ?>">
  <div class="feature__pane">
    <h1><?php the_title(); ?></h1>
    <h2 class="feature__label">Featured Article</h2>
  </div>
</section>

