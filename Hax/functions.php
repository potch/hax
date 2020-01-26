<?php

// Add styles to post editor (editor-style.css)
add_editor_style();

/*********
* Disable comment cookies
*/
remove_action('set_comment_cookies', 'wp_set_comment_cookies');

/* Disable Emojicons */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/*********
 * Register and define the Social Sharing setting
 */
function mozhacks_admin_init(){
  register_setting(
    'reading',
    'mozhacks_share_posts'
  );
  add_settings_field(
    'share_posts',
    __( 'Social sharing', 'mozhacks' ),
    'mozhacks_settings_field_share_posts',
    'reading',
    'default'
  );

  register_setting(
    'reading',
    'mozhacks_twitter_username'
  );
  add_settings_field(
    'twitter_username',
    __('Twitter username', 'mozhacks'),
    'mozhacks_settings_field_twitter_username',
    'reading',
    'default'
  );
}
add_action('admin_init', 'mozhacks_admin_init');

// Render the Social Sharing checkbox on the settings page
function mozhacks_settings_field_share_posts() {
	?>
	<div class="layout share-posts">
	<label>
		<input type="checkbox" id="mozhacks_share_posts" name="mozhacks_share_posts" value="1" <?php checked( '1', get_option('mozhacks_share_posts') ); ?> />
		<span>
			<?php _e('Add social sharing buttons to posts and pages', 'mozhacks'); ?>
		</span>
	</label>
	</div>
	<?php
}

// Renders the Twitter account setting field to share via.
function mozhacks_settings_field_twitter_username() { ?>
  <div class="layout twitter-username">
  <label>
    <input type="text" id="mozhacks_twitter_username" name="mozhacks_twitter_username" value="<?php echo get_option('mozhacks_twitter_username'); ?>">
    <p class="description"><?php _e('The Twitter account for attribution when sharing. Appears as "via @username" at the end of the tweet, and Twitter may suggest related accounts to follow. Leave this blank for no attribution.', 'mozhacks'); ?></p>
  </label>
  </div>
  <?php
}

/*********
* Remove WP version from head (helps us evade spammers/hackers who search for default metadata)
*/
remove_action('wp_head', 'wp_generator');


/**
* Replace the default title separator.
*/
function mozhacks_document_title_separator() {
  $sep = '–'; // this is en dash, not a hyphen
  return $sep;
}
add_filter('document_title_separator', 'mozhacks_document_title_separator');


/**
* Returns the page number currently being browsed.
*/
function mozhacks_page_number() {
  global $paged; // Contains page number.
  $sep = ' ' . mozhacks_document_title_separator() . ' ';
  if ($paged >= 2) {
    return $sep . sprintf(__('Page %s', 'mozhacks'), $paged);
  }
}


/**
* Prints a nice multipart page title.
* Used in the meta tags where wp_title() doesn't cut it.
*/
function mozhacks_meta_page_title() {
    $pagenum = mozhacks_page_number();
    $sep = ' ' . mozhacks_document_title_separator() . ' ';
    $sitename = get_bloginfo('name');

    if (is_single()) {
        $title = single_post_title('', false) . $sep . $sitename;
    }
    elseif (is_home() || is_front_page()) {
        $title = $sitename . $pagenum;
        if (get_bloginfo('description', 'display')) {
            $title .= $sep . get_bloginfo('description', 'display');
        }
    }
    elseif (is_page()) {
        $title = single_post_title('', false) . $sep . $sitename;
    }
    elseif (is_archive()) {
        if (is_category()) {
            $title = sprintf(__('Articles in “%s”', 'mozhacks'), single_cat_title('', false)) . $pagenum . $sep . $sitename;
        } elseif (is_tag()) {
            $title = sprintf(__('Articles tagged with “%s”','mozhacks'), single_tag_title('', false)) . $pagenum . $sep . $sitename;
        } elseif (is_author()) {
            $title = sprintf(__('Articles by %s', 'mozhacks'), get_the_author()) . $pagenum . $sep . $sitename;
        } elseif (is_day()) {
            $title = sprintf(__('Articles from %s', 'mozhacks'), get_the_date()) . $pagenum . $sep . $sitename;
        } elseif (is_month()) {
            $title = sprintf(__('Articles from %s', 'mozhacks'), get_the_date('F, Y')) . $pagenum . $sep . $sitename;
        } elseif (is_year()) {
            $title = sprintf(__('Articles from %s', 'mozhacks'), get_the_date('Y')) . $pagenum . $sep . $sitename;
        } else {
            $title = get_the_archive_title() . $pagenum . $sep . $sitename;
        }
    } elseif (is_search()) {
        $title = sprintf(__('Search results for “%s”', 'mozhacks'), esc_html(get_search_query())) . $pagenum . $sep . $sitename;
    } elseif (is_404()) {
      $title = __('Not Found', 'mozhacks') . $sep . $sitename;
    } else {
      $title = wp_title('', false, 'right') . $pagenum . $sep . $sitename;
    }

    echo $title;
}


/*
* Print the current page URL.
*/
function mozhacks_current_url() {
    global $wp;
    $current_url = esc_url(home_url(add_query_arg(array(),$wp->request)));

    echo $current_url;
}


/**
* Use auto-excerpts for meta description if hand-crafted exerpt is missing
*/
function mozhacks_meta_desc() {
  $post_desc_length = 30; // auto-excerpt length in number of words

  global $cat, $cache_categories, $wp_query, $wp_version;
  if(is_single() || is_page()) {
    $post = $wp_query->post;
    $post_custom = get_post_custom($post->ID);

    if(!empty($post->post_excerpt)) {
      $text = $post->post_excerpt;
    } else {
      $text = $post->post_content;
    }
    $text = do_shortcode($text);
    $text = str_replace(array("\r\n", "\r", "\n", "  "), " ", $text);
    $text = str_replace(array("\""), "", $text);
    $text = trim(strip_tags($text));
    $text = explode(' ', $text);
    if(count($text) > $post_desc_length) {
      $l = $post_desc_length;
      $ellipsis = '...';
    } else {
      $l = count($text);
      $ellipsis = '';
    }
    $description = '';
    for ($i=0; $i<$l; $i++)
      $description .= $text[$i] . ' ';

    $description .= $ellipsis;
  }
  elseif(is_category()) {
    $category = $wp_query->get_queried_object();
    if (!empty($category->category_description)) {
      $description = trim(strip_tags($category->category_description));
    } else {
      $description = single_cat_title('Articles posted in ', 'mozhacks');
    }
  }
  else {
    $description = trim(strip_tags(get_bloginfo('description')));
  }

  if($description) {
    echo $description;
  }
}


/*********
* Register sidebars
*/
if ( function_exists('register_sidebars') ) :
  register_sidebar(array(
    'name' => 'Home Page Sidebar',
    'id' => 'home',
    'description' => 'Displayed on the Home page',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widgettitle">',
    'after_title' => '</h3>',
  ));

  register_sidebar(array(
    'name' => 'Articles Page Sidebar',
    'id' => 'articles',
    'description' => 'Displayed on the main Articles page',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widgettitle">',
    'after_title' => '</h3>',
  ));

  register_sidebar(array(
    'name' => 'Demos Page Sidebar',
    'id' => 'demos',
    'description' => 'Displayed on the main Demos page',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widgettitle">',
    'after_title' => '</h3>',
  ));

  register_sidebar(array(
    'name' => 'About Page Sidebar',
    'id' => 'about',
    'description' => 'Displayed on the About page',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widgettitle">',
    'after_title' => '</h3>',
  ));

endif;


/*********
* Add theme support
*/
if ( function_exists( 'add_theme_support' ) ) {
  // This theme uses Featured Images (also known as post thumbnails)
  add_theme_support('post-thumbnails');

  // Let WordPress generate document titles
  add_theme_support('title-tag');

  // Let WordPress generate feeds
  add_theme_support('automatic-feed-links');
}


/*********
 * Set up custom roles and capabilities when the theme is activated.
 */
function mozhacks_custom_roles() {
  // Reviewers.
  // Can edit other's posts, but can't publish.
  $reviewcando = array(
    'read' =>  true,
    'read_private_posts' => true,
    'edit_posts' => true,
    'edit_others_posts' => true,
    'moderate_comments' => true,
  );
  remove_role('reviewer'); // remove first to reset, then add again
  add_role( 'reviewer', 'Reviewer', $reviewcando );
}
add_action( 'admin_init', 'mozhacks_custom_roles');


/*********
 * Allow Contributors to edit their posts and upload media
 */
function mozhacks_contributor_capabilities() {
     $contributor = get_role('contributor');
     $contributor->add_cap('upload_files');
     $contributor->add_cap('edit_published_posts');
}
add_action('admin_init', 'mozhacks_contributor_capabilities');


/*********
* Load various JavaScripts
*/
function mozhacks_load_scripts() {
  // Load the default jQuery
  wp_enqueue_script('jquery');

  // Load the threaded comment reply script
  if ( is_singular() && comments_open() && get_option('thread_comments') ) {
    wp_enqueue_script( 'comment-reply' );
  }

  // Check required fields on comment form
  wp_register_script( 'checkcomments', get_template_directory_uri() . '/js/fc-checkcomment.js' );
  if ( is_singular() && comments_open() && get_option('require_name_email') ) {
    wp_enqueue_script('checkcomments');
  }

  // Register and load the socialsharing script
  if ( is_singular() && get_option('mozhacks_share_posts') && !is_page(array('home','about','demos','articles')) ) {
    wp_register_script( 'socialshare', get_template_directory_uri() . '/js/socialshare.min.js', array('jquery', 'analytics'));
    if ( is_singular() && get_option('mozhacks_share_posts') && !is_page(array('home','about','demos','articles')) ) {
      wp_enqueue_script( 'socialshare' );
    }
  }

  // Register and load analytics script with jquery dependency
  wp_register_script( 'analytics', get_template_directory_uri() . '/js/analytics.js', array('jquery'));
  wp_enqueue_script( 'analytics', get_template_directory_uri() . '/js/analytics.js' );
}
add_action( 'wp_enqueue_scripts', 'mozhacks_load_scripts' );


/*********
* Make cleaner excerpts of any length
*/
function fc_excerpt($num) {
  $limit = $num+1;
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  array_pop($excerpt);
  $excerpt = implode(" ",$excerpt);
  echo $excerpt;
}

/**********
* Determine if the page is paged and should show posts navigation
*/
function fc_show_posts_nav() {
  global $wp_query;
  return ($wp_query->max_num_pages > 1) ? TRUE : FALSE;
}

/*********
* Determine if a previous post exists (i.e. that this isn't the first one)
*
* @param bool $in_same_cat Optional. Whether link should be in same category.
* @param string $excluded_categories Optional. Excluded categories IDs.
*/
function fc_previous_post($in_same_cat = false, $excluded_categories = '') {
  if ( is_attachment() )
    $post = & get_post($GLOBALS['post']->post_parent);
  else
    $post = get_previous_post($in_same_cat, $excluded_categories);
  if ( !$post )
    return false;
  else
    return true;
}

/*********
* Determine if a next post exists (i.e. that this isn't the last post)
*
* @param bool $in_same_cat Optional. Whether link should be in same category.
* @param string $excluded_categories Optional. Excluded categories IDs.
*/
function fc_next_post($in_same_cat = false, $excluded_categories = '') {
  $post = get_next_post($in_same_cat, $excluded_categories);
  if ( !$post )
    return false;
  else
    return true;
}

/*********
* Determines if the current page is the result of paged comments.
* This lets us prevent search engines from indexing lots of duplicate pages (since the post is repeated on every paged comment page).
*/
function is_comments_paged_url() {
  $pos = strpos($_SERVER['REQUEST_URI'], "comment-page");
  if ($pos === false) { return false; }
  else { return true; }
}

/*********
* Catch spambots with a honeypot field in the comment form.
* It's hidden from view with CSS so most humans will leave it blank, but robots will kindly fill it in to alert us to their presence.
* The field has an innucuous name -- 'age' in this case -- likely to be autofilled by a robot.
*/
function fc_honeypot( array $data ){
  if( !isset($_POST['comment']) && !isset($_POST['content'])) { die("No Direct Access"); }  // Make sure the form has actually been submitted

  if($_POST['age']) {  // If the Honeypot field has been filled in
    $message = _e('Sorry, you appear to be a spamming robot because you filled in the hidden spam trap field. To show you are not a spammer, submit your comment again and leave the field blank.', 'mozhacks');
    $title = 'Spam Prevention';
    $args = array('response' => 200);
    wp_die( $message, $title, $args );
    exit(0);
  } else {
	   return $data;
	}
}
add_filter('preprocess_comment','fc_honeypot');


/*********
* Comment Template for Mozilla Hacks theme
*/
function hacks_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  $comment_type = get_comment_type();
?>

 <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
  <?php if ( $comment_type == 'trackback' ) : ?>
  <?php elseif ( $comment_type == 'pingback' ) : ?>
  <?php else : ?>
    <b class="comment__title vcard">
      <cite class="author fn"><?php comment_author(); ?></cite>
    </b>
  <?php endif; ?>

    <?php if ($comment->comment_approved == '0') : ?>
      <p class="mod"><strong><?php _e('Your comment is awaiting moderation.'); ?></strong></p>
    <?php endif; ?>

    <blockquote class="comment__body">
      <?php comment_text(); ?>
    </blockquote>

    <a class="comment__meta" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title="Permanent link to this comment by <?php comment_author(); ?>"><abbr class="published" title="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></abbr> at <?php comment_time(); ?></a>

  <?php if (get_option('thread_comments') == true) : ?>
    <p class="comment__util"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?> <?php if ( current_user_can('edit_post', $comment->comment_post_ID) ) : ?><span class="edit"><?php edit_comment_link('Edit Comment','',''); ?></span><?php endif; ?></p>
  <?php endif; ?>
<?php
} /* end comment template */


/*********
* Display the_category(), excluding Featured
*/
function fc_category_minusfeat($separator) {
  $first_time = 1;
  foreach((get_the_category()) as $category) :
    if ($category->cat_name != 'Featured Article') :
      if ($first_time == 1) :
        echo '<a href="' . get_category_link( $category->term_id ) . '" rel="category tag" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>'  . $category->name.'</a>';
        $first_time = 0;
      else :
        echo $separator . '<a href="' . get_category_link( $category->term_id ) . '" rel="category tag" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
      endif;
    endif;
  endforeach;
}

/*********
* Display the_category(), excluding Demo
*/
function fc_category_minusdemo($separator) {
  $first_time = 1;
  foreach((get_the_category()) as $category) :
    if ($category->cat_name != 'Demo') :
      if ($first_time == 1) :
        echo '<a href="' . get_category_link( $category->term_id ) . '" rel="category tag" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>'  . $category->name.'</a>';
        $first_time = 0;
      else :
        echo $separator . '<a href="' . get_category_link( $category->term_id ) . '" rel="category tag" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
      endif;
    endif;
  endforeach;
}

/*********
* Display the_category(), excluding Demo and Featured Demo
*/
function fc_category_minusfeatdemo($separator) {
  $first_time = 1;
  foreach((get_the_category()) as $category) :
    if ( ($category->cat_name != 'Featured Demo') && ($category->cat_name != 'Demo') && ($category->cat_name != 'Uncategorized') ) :
      if ($first_time == 1) :
        echo '<a href="' . get_category_link( $category->term_id ) . '" rel="category tag" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>'  . $category->name.'</a>';
        $first_time = 0;
      else :
        echo $separator . '<a href="' . get_category_link( $category->term_id ) . '" rel="category tag" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
      endif;
    endif;
  endforeach;
}

/*********
* Register page rewrite rules for ordering pages by a field
*/
function tmh_page_order_rewrite_rules($rules) {
  $newrules = array();

  $by_pattern = 'by/([a-z]+)(/(asc|desc))?';
  $as_pattern = 'as/(title|brief|complete|list|thumbnail)';

  # we check for each combination of these individually. If we made by_pattern and as_pattern optional we would find the existing rules would be ignored.
  # paged search match
  $newrules['search/(.+?)/'.$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]&paged=$matches[6]';
  $newrules['search/(.+?)/'.$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&paged=$matches[5]';
  $newrules['search/(.+?)/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[1]&tmh_view_as=$matches[2]&paged=$matches[3]';

  # search match
  $newrules['search/(.+?)/'.$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?s=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]';
  $newrules['search/(.+?)/'.$by_pattern.'/?$'] = 'index.php?s=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]';
  $newrules['search/(.+?)/'.$as_pattern.'/?$'] = 'index.php?s=$matches[1]&tmh_view_as=$matches[2]';

  # paged category/author match
  $newrules['(category|author)/(.+?)/'.$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?$matches[1]_name=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[5]&tmh_view_as=$matches[6]&paged=$matches[7]';
  $newrules['(category|author)/(.+?)/'.$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?$matches[1]_name=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[5]&paged=$matches[6]';
  $newrules['(category|author)/(.+?)/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?$matches[1]_name=$matches[2]&tmh_view_as=$matches[3]&paged=$matches[4]';

  # category/author match
  $newrules['(category|author)/(.+?)/'.$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?$matches[1]_name=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[5]&tmh_view_as=$matches[6]';
  $newrules['(category|author)/(.+?)/'.$by_pattern.'/?$'] = 'index.php?$matches[1]_name=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[5]';
  $newrules['(category|author)/(.+?)/'.$as_pattern.'/?$'] = 'index.php?$matches[1]_name=$matches[2]&tmh_view_as=$matches[3]';

  # paged tag match
  $newrules['tag/(.+?)/'.$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?$tag=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]&paged=$matches[6]';
  $newrules['tag/(.+?)/'.$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?tag=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&paged=$matches[5]';
  $newrules['tag/(.+?)/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?tag=$matches[1]&tmh_view_as=$matches[2]&paged=$matches[3]';

  # tag match
  $newrules['tag/(.+?)/'.$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?tag=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]';
  $newrules['tag/(.+?)/'.$by_pattern.'/?$'] = 'index.php?tag=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]';
  $newrules['tag/(.+?)/'.$as_pattern.'/?$'] = 'index.php?tag=$matches[1]&tmh_view_as=$matches[2]';

  # paged dated y/m/d match
  $newrules['([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/'.$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&tmh_sort_by=$matches[4]&tmh_sort_dir=$matches[6]&tmh_view_as=$matches[7]&paged=$matches[8]';
  $newrules['([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/'.$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&tmh_sort_by=$matches[4]&tmh_sort_dir=$matches[6]&paged=$matches[7]';
  $newrules['([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&tmh_view_as=$matches[4]&paged=$matches[5]';

  # dated y/m/d match
  $newrules['([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/'.$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&tmh_sort_by=$matches[4]&tmh_sort_dir=$matches[6]&tmh_view_as=$matches[7]';
  $newrules['([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/'.$by_pattern.'/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&tmh_sort_by=$matches[4]&tmh_sort_dir=$matches[6]';
  $newrules['([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/'.$as_pattern.'/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&tmh_view_as=$matches[4]';

  # paged dated y/m match
  $newrules['([0-9]{4})/([0-9]{1,2})/'.$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[5]&tmh_view_as=$matches[6]&paged=$matches[7]';
  $newrules['([0-9]{4})/([0-9]{1,2})/'.$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[4]&paged=$matches[5]';
  $newrules['([0-9]{4})/([0-9]{1,2})/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&tmh_view_as=$matches[3]&paged=$matches[4]';

  # dated y/m match
  $newrules['([0-9]{4})/([0-9]{1,2})/'.$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[5]&tmh_view_as=$matches[6]';
  $newrules['([0-9]{4})/([0-9]{1,2})/'.$by_pattern.'/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&tmh_sort_by=$matches[3]&tmh_sort_dir=$matches[5]';
  $newrules['([0-9]{4})/([0-9]{1,2})/'.$as_pattern.'/?$'] = 'index.php?year=$matches[1]&monthnum=$matches[2]&tmh_view_as=$matches[3]';

  # paged dated y match
  $newrules['([0-9]{4})/'.$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]&paged=$matches[6]';
  $newrules['([0-9]{4})/'.$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&paged=$matches[5]';
  $newrules['([0-9]{4})/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?year=$matches[1]&tmh_view_as=$matches[2]&paged=$matches[3]';

  # dated y match
  $newrules['([0-9]{4})/'.$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?year=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]';
  $newrules['([0-9]{4})/'.$by_pattern.'/?$'] = 'index.php?year=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]';
  $newrules['([0-9]{4})/'.$as_pattern.'/?$'] = 'index.php?year=$matches[1]&tmh_view_as=$matches[2]';

  # paged page match
  $newrules['(.+?)/'.$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?pagename=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]&paged=$matches[6]';
  $newrules['(.+?)/'.$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?pagename=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&paged=$matches[5]';
  $newrules['(.+?)/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?pagename=$matches[1]&tmh_view_as=$matches[2]&paged=$matches[3]';

  # page match
  $newrules['(.+?)/'.$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?pagename=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]&tmh_view_as=$matches[5]';
  $newrules['(.+?)/'.$by_pattern.'/?$'] = 'index.php?pagename=$matches[1]&tmh_sort_by=$matches[2]&tmh_sort_dir=$matches[4]';
  $newrules['(.+?)/'.$as_pattern.'/?$'] = 'index.php?pagename=$matches[1]&tmh_view_as=$matches[2]';

  # paged direct match
  $newrules[$by_pattern.'/'.$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?tmh_sort_by=$matches[1]&tmh_sort_dir=$matches[3]&tmh_view_as=$matches[4]&paged=$matches[5]';
  $newrules[$by_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?tmh_sort_by=$matches[1]&tmh_sort_dir=$matches[3]&paged=$matches[4]';
  $newrules[$as_pattern.'/page/?([0-9]{1,})/?$'] = 'index.php?tmh_view_as=$matches[1]&paged=$matches[2]';

  # direct match (this happens on search and index pages)
  $newrules[$by_pattern.'/'.$as_pattern.'/?$'] = 'index.php?tmh_sort_by=$matches[1]&tmh_sort_dir=$matches[3]&tmh_view_as=$matches[4]';
  $newrules[$by_pattern.'/?$'] = 'index.php?tmh_sort_by=$matches[1]&tmh_sort_dir=$matches[3]';
  $newrules[$as_pattern.'/?$'] = 'index.php?tmh_view_as=$matches[1]';

  $rules = $newrules + $rules;
  return $rules;
}

/*********
* Register page rewrite variable so WP picks it up
*/
function tmh_page_order_query_vars($vars) {
  array_push($vars, 'tmh_sort_by', 'tmh_sort_dir', 'tmh_view_as');
  return $vars;
}

/*********
* Rewrite rules need to be flushed to work
*/
function tmh_flush_rules() {
  global $wp_rewrite;
  $wp_rewrite->flush_rules(false);
}
add_filter('rewrite_rules_array','tmh_page_order_rewrite_rules');
add_filter('query_vars','tmh_page_order_query_vars');
add_action('after_switch_theme', 'tmh_flush_rules');


/*********
* Returns the modified list of fields to return for the get posts query
*/
function tmh_posts_fields($fields) {
  if (is_admin()) {
    return $fields;
  }

  global $wpdb;
  $fields .= ", $wpdb->postmeta.meta_value as tmh_posthits";
  return $fields;
}
#add_filter('posts_fields', 'tmh_posts_fields');

/*********
* Returns the modified JOIN statement for accessing a single meta field
* in the get posts query
*/
function tmh_posts_join($join) {
  if (is_admin()) {
    return $join;
  }

  global $wpdb;
  # note the outer join and inclusion of the meta key HERE not in WHERE.
  # this us to allow posts without the matching meta key to show up
  $join .= " LEFT OUTER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key = '_tmh_pagehits'";
  return $join;
}
#add_filter('posts_join', 'tmh_posts_join');

/*********
* Returns the appropiate ORDER BY statement for query posts based on the query
* vars.
*/
function tmh_posts_orderby($orderby) {
  global $wp_query;
  global $wpdb;

  if (is_admin()) {
    return $orderby;
  }

  $sortby = @$wp_query->query_vars['tmh_sort_by'];
  if ( ! $sortdir = @$wp_query->query_vars['tmh_sort_dir']) {
    $sortdir = 'DESC';
  } else {
    $sortdir = strtoupper($sortdir) == 'DESC' ? 'DESC' : 'ASC';
  }
  switch ($sortby) {
    case 'views':
      $by_date = $wpdb->posts.'.post_date '.$sortdir;
      return $by_view.', '.$by_date;
    case 'comments':
      # by comment count and then by date
      $by_comment = $wpdb->posts.'.comment_count '.$sortdir;
      $by_date = $wpdb->posts.'.post_date '.$sortdir;
      return $by_comment.', '.$by_date;
    case 'date':
    default:
      return $wpdb->posts.'.post_date '.$sortdir;
  }
}
add_filter('posts_orderby', 'tmh_posts_orderby');

/*********
* Returns the current URL replacing as and by with the arguments given. If
* by or as are empty the argument is removed. If by or as are TRUE then the
* existing value is retained.
* Code is an almost complete replica of get_pagenum_link in link-template
*/
function tmh_by_as_url($by='', $as='') {
  global $wp_rewrite;

  $request = htmlspecialchars(remove_query_arg(array('tmh_sort_dir','tmh_sort_by', 'tmh_view_as')));

  $home_root = parse_url(get_option('home'));
  $home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
  $home_root = preg_quote( trailingslashit( $home_root ), '|' );

  $request = preg_replace('|^'. $home_root . '|', '', $request);
  $request = preg_replace('|^/+|', '', $request);

  if ( !$wp_rewrite->using_permalinks() || is_admin() ) {
    $base = trailingslashit( get_bloginfo( 'home' ) );

    if ( strlen($by) > 0 ) {
      $result = add_query_arg( 'tmh_sort_dir', $by, $base . $request );
    } else {
      $result = $base . $request;
    }

    if ( strlen($as) > 0 ) {
      $result = add_query_arg( 'tmh_view_as', $as, $result );
    }
  } else {
    $qs_regex = '|\?.*?$|';
    preg_match( $qs_regex, $request, $qs_match );

    if ( !empty( $qs_match[0] ) ) {
      $query_string = $qs_match[0];
      $request = preg_replace( $qs_regex, '', $request );
    } else {
      $query_string = '';
    }
    if ($by !== TRUE) {
      $request = preg_replace( '|by/\w+/?|', '', $request);
    }
    if ($as !== TRUE) {
      $request = preg_replace( '|as/\w+/?|', '', $request);
    }

    # extract the page number, if set
    preg_match( '|page/\d+/?$|', $request, $pager);
    $pager = @$pager[0];
    $request = preg_replace( '|page/\d+/?$|', '', $request);

    $request = preg_replace( '|^index\.php|', '', $request);
    $request = ltrim($request, '/');

    $base = trailingslashit( get_bloginfo( 'url' ) );

    if ( $wp_rewrite->using_index_permalinks() && ( strlen($by) > 0 || strlen($as) > 0 || '' != $request ) )
      $base .= 'index.php/';

    if ( strlen($by) > 0 AND $by !== TRUE) {
      $request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( 'by/' . $by, 'tmh_sort_by' );
      $result = $base . $request . $pager . $query_string;
    }
    if ( strlen($as) > 0 AND $as !== TRUE) {
      $request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( 'as/' . $as, 'tmh_view_as' );
      $result = $base . $request . $pager . $query_string;
    }
  }
  return $result;
}

/*********
* Remove /as urls from the paging links
*/
function tmh_get_pagenum_link($url) {
  $url = preg_replace( '|as/\w+/?|', '', $url);
  return $url;
}
add_filter('get_pagenum_link', 'tmh_get_pagenum_link');

/*********
* Echos string_to_echo if the query variable query_key has the value_to_check.
* Set default to true if this call should return the classes string when the
* query var is not set or is empty.
*/
function tmh_has_query_var($query_key, $value_to_check, $string_to_echo, $default=FALSE) {
  global $wp_query;

  $mappings = tmh_article_to_demo_mappings();

  if (isset($wp_query->query_vars[$query_key]) AND ($wp_query->query_vars[$query_key] == $value_to_check)) {
    echo stripslashes(wp_filter_kses($string_to_echo));
  } elseif (isset($wp_query->query_vars[$query_key]) AND ($wp_query->query_vars[$query_key] == array_search($value_to_check, $mappings))) {
    echo stripslashes(wp_filter_kses($string_to_echo));
  } elseif ( $default AND ( ! isset($wp_query->query_vars[$query_key]) OR empty($wp_query->query_vars[$query_key]))) {
    echo stripslashes(wp_filter_kses($string_to_echo));
  }
}

/*********
* Hook to setup the view as cookie and set the parameter if required.
*/
function tmh_parse_query($wp) {
  $query_vars = &$wp->query_vars;

  if (isset($query_vars['tmh_view_as'])) {
    $view_as = $query_vars['tmh_view_as'];
  } elseif (isset($_COOKIE['tmh_view_as_'.COOKIEHASH])) {
    $view_as = $_COOKIE['tmh_view_as_'.COOKIEHASH];
  } else {
    $view_as = 'brief';
  }

  # sanitize
  $view_as = stripslashes($view_as);
  $view_as = esc_attr($view_as);
  $view_as = strtolower($view_as);

  # demo views. We convert these to article view templates. Don't forget to
  # change this in
  $key = array_search($view_as, tmh_article_to_demo_mappings());
  $view_as = ($key === FALSE) ? $view_as : $key;

  $query_vars['tmh_view_as'] = $view_as;
}
add_action('parse_query', 'tmh_parse_query');

/*********
* Article => Demo Mappings
*/
function tmh_article_to_demo_mappings() {
  return array(
    'brief'     => 'thumbnail',
    'title'     => 'list',
    'complete'  => 'complete',
  );
}

/*********
* Get the categories for all posts that are themselves in a particular given category
*/
$tmh_categories_of_posts_in_category_categories;
function tmh_categories_of_posts_in_category($cat_id, $exclude_cats=array(), $show_count=TRUE) {
  // rather than do a complex multi nested select in the DB, we'll do a multistage process
  // first get the posts which are a member of cat_id (and it's child categories)
  $wp_query = new WP_Query();
  add_filter('posts_fields', 'tmh_categories_of_posts_in_category_fields');
  $post_ids = $wp_query->query('cat='.$cat_id.'&posts_per_page=-1');
  remove_filter('posts_fields', 'tmh_categories_of_posts_in_category_fields');
  array_walk($post_ids, 'tmh_flatten_array');

  # next, get the categories of the posts we just retrieved
  $categories = wp_get_object_terms(
    $post_ids, 'category', array(
      'fields' => 'ids'
  ));
  $counts = array_count_values($categories);
  unset($counts[$cat_id]);

  // remove any excluded categories
  $counts = array_diff_key($counts, array_flip($exclude_cats));

  // for the moment passing extra arguments to filters isn't something we can do
  // so a global needs to be used.
  global $tmh_categories_of_posts_in_category_categories;
  $tmh_categories_of_posts_in_category_categories = array_keys($counts);

  add_filter('list_terms_exclusions', 'tmh_list_terms_exclusions');
  $categories = wp_list_categories("show_count=$show_count&hierarchical=0&title_li&echo=0");
  remove_filter('list_terms_exclusions', 'tmh_list_terms_exclusions');

  // update counts
  if ($show_count) {
    foreach ($counts as $id => $count) {
      $categories = preg_replace("/(cat-item-$id.+<\/a>\s+)\((\d+)\)/i", '${1}('.$count.')', $categories);
    }
  }
  unset($wp_query);
  echo $categories;
}
function tmh_categories_of_posts_in_category_fields($fields) {
  global $wpdb;
  return "$wpdb->posts.ID";
}
function tmh_flatten_array(&$item, $key) {
  $item = current($item);
}
function tmh_list_terms_exclusions($exclusions) {
  global $tmh_categories_of_posts_in_category_categories;
  $exclusions = 'AND tt.term_id IN (' . implode(',', $tmh_categories_of_posts_in_category_categories) . ')';
  unset($tmh_categories_of_posts_in_category_categories);
  return $exclusions;
}

/*********
* Render debug information nicely
*/
function pr($obj) {
  echo '<pre style="white-space: pre-wrap; background-color: black; color: white">';
  print_r($obj);
  echo '</pre>';
}

/*********
* Hook which renders any SQL query to the screen when it happens.
* Uncomment the pr to have these displayed
*/
function tmh_display_query_sql($query) {
  global $wp_query;
  // pr(array_filter($wp_query->query_vars));
  // pr($query);
  return $query;
}
add_filter('posts_request', 'tmh_display_query_sql'); // use query for all db activity

/*********
* To use when a loop is needed in a page. Use $args to call query_posts and then
* use the template indicated to render the posts. The template gets included once
* per post.
*
* Orginally by Lyza Gardner; see http://www.cloudfour.com/533/wordpress-taking-the-hack-out-of-multiple-custom-loops/
*
* @param Array $args               Wordpress-style arguments; passed on to query_posts
*                                  'template' => name of post template to use for posts
* @return Array of WP $post objs   Matching posts, if you should need them.
*/
function fc_custom_loop($args) {
    global $wp_query;
    global $post;
    $post_template_dir = 'views';
    /* The 'template' element should be the name of the PHP template file
       to use for rendering the matching posts. It should be the name of file,
       without path and without '.php' extension. e.g. the default value 'default'
       is $post_template_dir/default.php
    */
    $defaults = Array('template' => 'article-brief' );

    $opts = wp_parse_args($args, $defaults);

    // Bring arguments into local scope, vars prefixed with $loop_
    extract($opts, EXTR_PREFIX_ALL, 'loop');

    // Preserve the current query object and the current global post before messing around.
    $temp_query = clone $wp_query;
    $temp_post  = clone $post;

    // Wildcard substitution
    if (isset($wp_query->query_vars['tmh_view_as'])) {
      $view_as = $wp_query->query_vars['tmh_view_as'];
    } else {
      $view_as = 'brief';
    }

    $tpl = explode('-', $loop_template);

    // Article => Demo mappings. For demo templates we have to convert this across
    $mappings = tmh_article_to_demo_mappings();

    switch ($tpl[0]) {
      case 'demo':
        $view_as = $mappings[$view_as];
        break;
    }

    $loop_template = str_replace(
      array('%view%'),
      array($view_as),
      $loop_template
    );

    $template_path = sprintf('%s/%s/%s.php', dirname(__FILE__), $post_template_dir, $loop_template);

    if(!file_exists($template_path)) {
        printf ('<p class="fail">Sorry, the template you are trying to use ("%s")
            in %s() does not exist (%s).',
            $template,
            __FUNCTION__,
            __FILE__);
        return false;
    }
    /* Allow for display of posts in order passed in post__in array
       [as the 'orderby' arg doesn't seem to work consistently without giving it some help]
       If 'post__in' is in args and 'orderby' is set to 'none', just grab those posts,
       in the order provided in the 'post__in' array.
    */
    if($loop_orderby && $loop_orderby == 'none' && $loop_post__in)
    {
        foreach($loop_post__in as $post_id)
            $loop_posts[] = get_post($post_id);
    }
    else
        $loop_posts = query_posts($args);

    /* Utility vars for the loop; in scope in included template */
    $loop_count             = 0;
    $loop_odd               = false;
    $loop_even              = false;
    $loop_first             = true;
    $loop_last              = false;
    $loop_css_class         = '';   // For convenience
    $loop_size = sizeof($loop_posts);
    $loop_owner = $temp_post;       /* The context from within this loop is called
                                       the global $post before we query */

    foreach($loop_posts as $post)
    {
        $loop_count += 1;
        ($loop_count % 2 == 0) ? $loop_even = true : $loop_even = false;
        ($loop_count % 2 == 1) ? $loop_odd  = true : $loop_odd  = false;
        ($loop_count == 1) ?     $loop_first = true : $loop_first = false;
        ($loop_count == $loop_size) ? $loop_last = true : $loop_last = false;
        ($loop_even) ? $loop_css_class = 'even' : $loop_class = 'odd';
        setup_postdata($post);
        include($template_path);
    }
    $wp_query = clone $temp_query;  // Put the displaced query and post back into global scope
    $post = clone $temp_post;       // And set up the post for use.
    setup_postdata($post);
    return $loop_posts;
}

/*********
* Returns the number of posts contained within a category, including sub-categories.
* Posts are only counted once even if they are posted to multiple sub-categories.
*/
function tmh_unique_posts_in_category($cat_id) {
  $wp_query = new WP_Query();
  $posts = $wp_query->query('cat='.$cat_id.'&posts_per_page=-1');
  $posts = $wp_query->post_count;
  unset($wp_query);
  return $posts;
}

/*********
* Returns author listing HTML
* Only authors who have active posts and a bio are included
*/
function dw_list_authors() {
  global $wpdb;

  $users = get_users(array());

  // Do a custom query to get post counts for everyone
  // This will save hundreds of queries over "WordPress-style" code
  $postsByUsersQuery = 'SELECT post_author, COUNT(*) as count, meta_value AS description FROM '.$wpdb->posts.' p, '.$wpdb->usermeta.' um WHERE post_status="publish" AND um.meta_key = "description" AND um.user_id = p.post_author AND meta_value != "" AND post_type = "post" GROUP BY post_author';
  $postsByUsersResult = $wpdb->get_results($postsByUsersQuery, ARRAY_A);
  $postsByUsersIndex = array();
  foreach($postsByUsersResult as $result) {
    $postsByUsersIndex[$result['post_author']] = array('count'=>$result['count'], 'description'=>$result['description']);
  }

  // Sort by number of posts
  foreach($users as $user) {
    $count = $postsByUsersIndex[$user->ID]['count'];
    if($count == '') { $count = 0; }
    $user->total_posts = $count;
    $user->description = $postsByUsersIndex[$user->ID]['description'];
  }
  usort($users, 'sort_objects_by_total_posts');
  $users = array_reverse($users);

  // Prep column output
  $column1 = $column2 = array();
  $which = true;

  // Generate output for authors
  foreach($users as $index=>$user) {
    if($user->total_posts > 1 && $user->description) {
      $item = '<li class="vcard" id="author-'.$user->user_login.'">';
      $item.= '<h3><a class="url" href="'.get_author_posts_url($user->ID).'">';
      if (function_exists('get_avatar')) {
        $item.= get_avatar($user->user_email, 48);
      }
      $item.= '<cite class="fn">'.$user->display_name.'</cite> <span class="post-count">'.$user->total_posts.' post'.($user->total_posts > 1 ? 's' : '').'</span></a></h3>';
      $item.= '<p class="desc">'.$user->description.'</p>';
      $item.= dw_get_author_meta($user->ID);
      $item.= '</li>';

      if($which) {
        array_push($column1, $item);
      }
      else {
        array_push($column2, $item);
      }
      $which = !$which;
    }
  }

  $return = '<ul class="author-list">'.implode('', $column1).'</ul>';
  $return.= '<ul class="author-list">'.implode('', $column2).'</ul>';

  return $return;
}

/*********
* Sorts WordPress users by Object key (total posts)
*/
function sort_objects_by_total_posts($a, $b) {
  if($a->total_posts == $b->total_posts){ return 0 ; }
  return ($a->total_posts < $b->total_posts) ? -1 : 1;
}

/*********
* Adds "Twitter" and "Facebook" fields to the user profile form
*/
function additional_contactmethods($user_contactmethods) {
  $user_contactmethods['twitter'] = 'Twitter Username';
  $user_contactmethods['facebook'] = 'Facebook URL';
  $user_contactmethods['gplus'] = 'Google+ URL';
  return $user_contactmethods;
}
add_filter('user_contactmethods', 'additional_contactmethods');

/*********
* Shows Facebook, Twitter, and Website links for authors
*/
function dw_get_author_meta($authorID = null) {
  $twitterHandle = get_the_author_meta('twitter', $authorID);
  $facebookURL = get_the_author_meta('facebook', $authorID);
  $website = get_the_author_meta('url', $authorID);
  $gplusURL = get_the_author_meta('gplus', $authorID);
  $return = '';

  if($website || $facebookURL || $twitterHandle || $gplusURL):
    $return.= '<ul class="author-meta fa-ul">';
    if($website):
      $return.= '<li><i class="fa-li fa fa-globe"></i><a href="'. $website. '" class="website" rel="me">'. str_replace('http://', '', $website). '</a></li>';
    endif;
    if($twitterHandle):
      $return.= '<li><i class="fa-li fa fa-twitter"></i><a href="http://twitter.com/'. $twitterHandle. '" class="twitter" rel="me">@'. $twitterHandle. '</a></li>';
    endif;
    if($facebookURL):
      $return.= '<li><i class="fa-li fa fa-facebook"></i><a href="'. $facebookURL. '" class="facebook" rel="me">Facebook</a></li>';
    endif;
    if($gplusURL):
      $return.= '<li><i class="fa-li fa fa-google-plus"></i><a href="'. $gplusURL. '" class="gplus" rel="me">Google+</a></li>';
    endif;
    $return.= '</ul>';
  endif;

  return $return;
}

/*
* Try to clean up email addresses a bit.
*/
function hacks_author($name) {
  return preg_replace('/@.+/', '', $name);
}

/*********
* Returns author list, but not HTML which is gross.
* Only authors who have active posts and a bio are included
*/
function hacks_list_authors() {
  global $wpdb;

  $users = get_users(array());

  // Do a custom query to get post counts for everyone
  // This will save hundreds of queries over "WordPress-style" code
  $postsByUsersQuery = 'SELECT post_author, COUNT(*) as count, meta_value AS description FROM '.$wpdb->posts.' p, '.$wpdb->usermeta.' um WHERE post_status="publish" AND um.meta_key = "description" AND um.user_id = p.post_author AND meta_value != "" AND post_type = "post" GROUP BY post_author';
  $postsByUsersResult = $wpdb->get_results($postsByUsersQuery, ARRAY_A);
  $postsByUsersIndex = array();
  foreach($postsByUsersResult as $result) {
    $postsByUsersIndex[$result['post_author']] = array('count'=>$result['count'], 'description'=>$result['description']);
  }

  // Sort by number of posts
  foreach($users as $user) {
    $count = $postsByUsersIndex[$user->ID]['count'];
    if($count == '') { $count = 0; }
    $user->total_posts = $count;
    $user->description = $postsByUsersIndex[$user->ID]['description'];
  }
  usort($users, 'sort_objects_by_total_posts');
  $users = array_reverse($users);

  // Prep column output
  $return = array();

  // Generate output for authors
  foreach($users as $index=>$user) {
    if($user->total_posts > 1 && $user->description) {
      array_push($return, $user);
    }
  }

  return $return;
}

function hacks_category_link($category) {
  echo '<a href="' . get_category_link( $category->term_id ) . '" rel="category tag" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>'  . $category->name.'</a>';
}

/*********
* Display the_category(), excluding Featured
*/
function hacks_category_list() {
  $idx = 0;
  $categories = get_the_category();
  $num = count($categories);
  foreach($categories as $category) :
    $idx++;
    hacks_category_link($category);
    if ($idx < $num) :
      if ($num > 2):
        echo ', ';
      endif;
      if ($num == 2 || ($num > 2 && $num - $idx == 1)):
        echo ' and ';
      endif;
    endif;
  endforeach;
}

?>
