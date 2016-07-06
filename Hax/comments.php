<?php // Do not delete these lines
  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');
  if ( post_password_required() ) {
    echo '<p class="nocomments">This post is password protected. Enter the password to view comments.</p>';
    return;
  }

  /* This variable is for alternating comment background */
  $oddcomment = 'alt';
?>

<?php /* You can start editing here. */ ?>

<?php if ( have_comments() || comments_open() ) : // If there are comments OR comments are open ?>
<section class="discussion">
  <hr class="dino">
  <div class="comments">
    <header class="comments__head">
      <h3><?php comments_number('No comments yet', 'One comment', '% comments' );?></h3>
    </header>

    <?php if ( have_comments() ) : // If there are comments ?>
      <ol class="comments__list" class="hfeed">
      <?php wp_list_comments('type=all&style=ol&callback=hacks_comment'); // Comment template is in functions.php ?>
      </ol>
    <?php endif; ?>
  </div><?php // end .comments ?>

<?php if (comments_open()) : ?>
  <div class="response" id="respond">
    <?php if ( get_option('comment_registration') && !$user_ID ) : // If registration is required and you're not logged in, show a message ?>
    <p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

    <?php else : // else show the form ?>
    <form id="comment-form" action="<?php echo esc_attr(get_option('siteurl')); ?>/wp-comments-post.php" method="post">
        <h3><?php comment_form_title( __('Post Your Comment'), __('Reply to %s' ) ); ?></h3>
        <p id="cancel-comment-reply"><?php cancel_comment_reply_link('Cancel Reply'); ?></p>
      <?php if ( $user_ID ) : ?>
        <div class="self"><?php printf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a class="logout" href="%3$s">Log out?</a>', 'mozhacks' ), admin_url( 'profile.php' ), esc_html($user_identity), wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ); ?></div>
      <?php else : ?>
        <div class="field" id="cmt-name">
          <label for="author"><?php _e('Your name', 'mozhacks'); ?> <?php if ($req) :?><abbr title="<?php _e('(required)', 'onemozilla'); ?>">*</abbr><?php endif; ?></label>
          <input type="text" name="author" id="author" size="25" <?php if ($req) echo "required aria-required='true'"; ?>>
        </div>
        <div class="field" id="cmt-email">
          <label for="email"><?php _e('Your e-mail', 'mozhacks'); ?> <?php if ($req) :?><abbr title="<?php _e('(required)', 'onemozilla'); ?>">*</abbr><?php endif; ?></label>
          <input type="email" name="email" id="email" size="25" <?php if ($req) echo "required aria-required='true'"; ?>>
        </div>
        <div id="cmt-ackbar">
          <label for="age"><?php _e('Spam robots, please fill in this field. Humans should leave it blank.', 'mozhacks'); ?></label>
          <input type="text" name="age" id="age" size="4" tabindex="-1">
        </div>
      <?php endif; ?>
      <div class="field" id="cmt-cmt">
        <label for="comment"><?php _e('Your comment', 'mozhacks'); ?></label> <textarea name="comment" id="comment" cols="50" rows="10" required="required" aria-required="true"></textarea>
      </div>
      <div class="field" id="comment-submit">
        <button name="submit" type="submit"><?php _e('Submit Comment', 'mozhacks'); ?></button>
      </div>
      <?php comment_id_fields(); ?>
      <?php do_action('comment_form', $post->ID); ?></div>
    </form>
    <?php endif; // end if reg required and not logged in ?>
  </div><?php // end #respond ?>
</section><?php // end .discussion ?>
<script>jQuery("#comment-form").submit(function() { return fc_checkform(<?php if ($req) : echo "'req'"; endif; ?>); });</script>

<?php else : // else comments are closed ?>

<p class="comments__closed"><b>Comments are closed for this article.</b></p>

<?php endif; // end if comments open ?>

<?php endif; // if you delete this the sky will fall on your head ?>
