<?php if (( is_single() || is_page() ) && (!is_front_page()) ) : $pageClass = 'home'; ?>

<?php endif ?>

<!doctype html>
<html lang="en-US">
<head data-template-path="<?php echo get_template_directory_uri(); ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php get_template_part('includes/metadata'); ?>

  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/highlight.js/8.6.0/styles/solarized_light.min.css">

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
        <div class="branding block block--3">
          <h1>
            <a href="<?php bloginfo('url'); ?>">
              <img class="branding__logo" src="<?php echo get_template_directory_uri(); ?>/img/mdn-logo-mono.svg">
              <img class="branding__wordmark" src="<?php echo get_template_directory_uri(); ?>/img/wordmark.svg" alt="Mozilla">
              <span class="branding__title">Hac<span class="logo-askew">k</span>s</span>
            </a>
          </h1>
        </div>
        <div class="search block block--2">
          <form class="search__form" method="get" action="<?php bloginfo('url'); ?>/">
            <input type="search" name="s" class="search__input" placeholder="Search Mozilla Hacks" value="<?php the_search_query(); ?>">
            <i class="fa fa-search search__badge"></i>
          </form>
        </div>
        <nav class="social">
          <a class="social__link youtube" href="http://www.youtube.com/user/mozhacks" title="YouTube"><i class="fa fa-youtube" aria-hidden="true"></i><span>Hacks on YouTube</span></a>
          <a class="social__link twitter" href="https://twitter.com/mozhacks" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i><span>@mozhacks on Twitter</span></a>
          <a class="social__link rss" href="<?php bloginfo('rss2_url'); ?>" title="RSS Feed"><i class="fa fa-rss" aria-hidden="true"></i><span>Hacks RSS Feed</span></a>
        </nav>
      </div>
    </header>
