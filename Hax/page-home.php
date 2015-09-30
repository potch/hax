<?php get_header();
$featured_id = get_cat_ID('Featured Article');
$categories = array(
  "Add-ons",
  "CSS",
  "Developer Tools",
  "Firefox",
  "Firefox OS",
  "Games",
  "Javascript",
  "Mobile",
  "Performance",
  "Security",
  "SVG",
  "WebGL"
);
?>

<?php fc_custom_loop('cat='.$featured_id.'&posts_per_page=1&template=home-head-featarticles'); ?>

<section class="recent content section row">
  <div class="block block--2">
    <h2 class="heading">Recent Articles</h2>
    <ul class="article-list">
      <?php fc_custom_loop('template=article-list-short'); ?>
    </ul>
  </div>
  <div class="block block--1">
    <h2 class="heading">Categories</h2>
    <ul class="category-list" role="navigation">
      <?php foreach ($categories as $id) : ?>
        <?php $cat = get_category(get_cat_ID($id)); ?>
        <?php if ($cat->cat_ID): ?>
          <li class="category-list__link"><a href="<?php echo get_category_link($cat->cat_ID); ?>"><?php echo $cat->name; ?></a></li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php get_footer(); ?>
