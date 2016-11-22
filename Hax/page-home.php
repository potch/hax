<?php get_header();
$featured_id = get_cat_ID('Featured Article');
$categories = array(
  "Add-ons",
  "Animations",
  "Apps",
  "Canvas",
  "CSS",
  "Developer Tools",
  "Firefox",
  "Firefox OS",
  "Games",
  "HTML5",
  "Javascript",
  "Mobile",
  "Performance",
  "Security",
  "SVG",
  "Video",
  "WebGL"
);
?>

<?php fc_custom_loop('cat='.$featured_id.'&posts_per_page=1&template=home-head-featarticles'); ?>

<section class="recent content section">
  <div class="row">
    <div class="block block--3">
      <h2 class="heading">Recent Articles</h2>
      <ul class="article-list">
        <?php fc_custom_loop('posts_per_page=1&template=article-list'); ?>
      </ul>
    </div>
    <?php get_template_part('newsletter'); ?>
  </div>
  <div class="row">
    <div class="block block--2">
      <ul class="article-list">
        <?php fc_custom_loop('template=article-list&offset=1'); ?>
      </ul>
      <h3 class="read-more"><a href="<?php echo get_permalink(get_page_by_path('articles'));?>page/2/">Browse All Articles &rarr;</a></h3>
    </div>
    <div class="block block--1 block--minor">
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
  </div>
</section>

<?php get_footer(); ?>
