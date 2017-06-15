<?php if (( is_single() || is_page() ) && (!is_front_page()) ) : $pageClass = 'home'; ?>

<?php endif ?>

<!doctype html>
<html lang="en-US">
<head data-template-path="<?php echo get_template_directory_uri(); ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Metadata for Metro -->
  <meta name="application-name" content="Mozilla Hacks" />

  <!-- Favicons -->
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-60x60.png" />
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-touch-icon-152x152.png" />
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-128.png" sizes="128x128" />
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/favicons/mstile-144x144.png" />
  <meta name="msapplication-square70x70logo" content="<?php echo get_template_directory_uri(); ?>/img/favicons/mstile-70x70.png" />
  <meta name="msapplication-square150x150logo" content="<?php echo get_template_directory_uri(); ?>/img/favicons/mstile-150x150.png" />
  <meta name="msapplication-wide310x150logo" content="<?php echo get_template_directory_uri(); ?>/img/favicons/mstile-310x150.png" />
  <meta name="msapplication-square310x310logo" content="<?php echo get_template_directory_uri(); ?>/img/favicons/mstile-310x310.png" />


  <!-- Metadata for Facebook -->
  <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
  <meta property="og:url" content="<?php if (is_singular()) : the_permalink(); else : bloginfo('url'); endif; ?>">
  <meta property="og:title" content="<?php if (is_singular()) : single_post_title(); else : bloginfo('name'); endif; ?>">
  <meta property="og:description" content="<?php fc_meta_desc(); ?>">
<?php if (is_singular() && $thumbs = get_attached_media('image')) : ?>
  <?php foreach ($thumbs as $thumb) : ?>
    <?php $thumb = wp_get_attachment_image_src( $thumb->ID, 'large' ); ?>
    <meta property="og:image" content="<?php echo $thumb['0']; ?>">
  <?php endforeach; ?>
<?php endif; ?>

  <!-- Metadata for Twitter -->
  <meta property="twitter:title" content="<?php if (is_singular()) : single_post_title(); else : bloginfo('name'); endif; ?>">
  <meta property="twitter:description" content="<?php fc_meta_desc(); ?>">
<?php if (is_singular() && $thumbs = get_attached_media('image')) : ?>
  <meta name="twitter:card" content="summary_large_image">
  <?php foreach ($thumbs as $thumb) : ?>
    <?php $thumb = wp_get_attachment_image_src( $thumb->ID, 'large' ); ?>
    <meta property="twitter:image" content="<?php echo $thumb['0']; ?>">
  <?php endforeach; ?>
<?php else : ?>
  <meta name="twitter:card" content="summary">
<?php endif; ?>
<?php if (get_option('mozhacks_twitter_username')) : ?>
  <meta name="twitter:site" content="@<?php echo sanitize_text_field(get_option('mozhacks_twitter_username')); ?>">
<?php endif; ?>

  <link rel="alternate" type="application/rss+xml" title="Mozilla Hacks &#8211; the Web developer blog RSS Feed" href="<?php bloginfo('rss2_url'); ?>">

  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
  <link href="//addons.cdn.mozilla.net/static/css/tabzilla/tabzilla.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/highlight.js/8.6.0/styles/solarized_light.min.css">

  <title><?php if (( is_single() || is_page() ) && (!is_front_page()) ) : ?><?php wp_title($sep = ''); ?> &#x2605;
    <?php elseif ( is_search() ) : ?>Search results for &#8220;<?php the_search_query(); ?>&#8221; &#x2605;
    <?php elseif ( is_category('Demo') ) : ?>Demos &#x2605;
    <?php elseif ( is_category('Featured Demo') ) : ?>Featured Demos &#x2605;
    <?php elseif ( is_category('Featured Article') ) : ?>Featured Articles &#x2605;
    <?php elseif ( is_category() ) : ?><?php single_cat_title(); ?> Articles &#x2605;
    <?php elseif ( is_author() ) : ?>Articles by <?php echo get_userdata(intval($author))->display_name; ?> &#x2605;
    <?php elseif ( is_tag() ) : ?>Articles tagged &#8220;<?php single_tag_title(); ?>&#8221; &#x2605;
    <?php elseif ( is_day() ) : ?>Articles for <?php the_time('F jS, Y'); ?> &#x2605;
    <?php elseif ( is_month() ) : ?>Articles for <?php the_time('F Y'); ?> &#x2605;
    <?php elseif ( is_year() ) : ?>Articles for <?php the_time('Y'); ?> &#x2605;
    <?php elseif ( is_404() ) : ?>Not Found &#x2605;
    <?php elseif ( is_home() ) : ?>Articles &#x2605;
    <?php endif; ?>
    <?php bloginfo('name'); ?>
  </title>

  <script type="text/javascript">
    window.hacks = {};
    // http://cfsimplicity.com/61/removing-analytics-clutter-from-campaign-urls
    var removeUtms  =   function(){
        var l = window.location;
        if( l.hash.indexOf( "utm" ) != -1 ){
            var anchor = l.hash.match(/#(?!utm)[^&]+/);
            anchor  =   anchor? anchor[0]: '';
            if(!anchor && window.history.replaceState){
                history.replaceState({},'', l.pathname + l.search);
            } else {
                l.hash = anchor;
            }
        };
    };
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-35433268-8'],
              ['_setAllowAnchor', true]);
    _gaq.push (['_gat._anonymizeIp']);
    _gaq.push(['_trackPageview']);
    _gaq.push( removeUtms );
    (function(d, k) {
      var ga = d.createElement(k); ga.type = 'text/javascript'; ga.async = true;
      ga.src = 'https://ssl.google-analytics.com/ga.js';
      var s = d.getElementsByTagName(k)[0]; s.parentNode.insertBefore(ga, s);
    })(document, 'script');
  </script>

  <?php wp_head(); ?>
</head>
<body>
  <div class="outer-wrapper">
    <header class="section section--fullwidth header">
      <div class="masthead row">
        <div id="tabzilla">
          <a href="https://www.mozilla.org/">Mozilla</a>
        </div>
        <div class="branding block block--3">
          <h1><a href="<?php bloginfo('url'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/mdn-logo-mono.svg" alt="Mozilla"><span>Hac<span class="logo-askew">k</span>s</span></a></h1>
        </div>
        <div class="search block block--2">
          <form class="search__form" method="get" action="<?php bloginfo('url'); ?>/">
            <input type="search" name="s" class="search__input" placeholder="Search Mozilla Hacks" value="<?php the_search_query(); ?>">
            <i class="fa fa-search search__badge"></i>
          </form>
        </div>
        <nav class="social">
          <a class="social__link youtube" href="http://www.youtube.com/user/mozhacks" title="YouTube"><i class="fa fa-youtube"></i><span>Hacks on YouTube</span></a>
          <a class="social__link twitter" href="https://twitter.com/mozhacks" title="Twitter"><i class="fa fa-twitter"></i><span>@mozhacks on Twitter</span></a>
          <a class="social__link rss" href="<?php bloginfo('rss2_url'); ?>" title="RSS Feed"><i class="fa fa-rss"></i><span>Hacks RSS Feed</span></a>
        </nav>
      </div>
    </header>
