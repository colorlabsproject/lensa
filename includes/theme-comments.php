<?php
/*-----------------------------------------------------------------------------------*/
/* CoLabs - List Comment */
/*-----------------------------------------------------------------------------------*/
function colabs_list_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
?>

	<li <?php comment_class(); ?>>
		<div id="comment-<?php comment_ID(); ?>" class="comment-entry">
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'colabsthemes' ); ?></em>
			<?php endif; ?>

			<div class="comment-avatar">
			<?php commenter_link() ?>
			</div>

			<div class="comment-content">
				<div class="comment-meta">
				  <span class="comment-author"><?php echo get_comment_author_link(); ?></span> 
				  <time><?php printf( __( '%1$s', 'colabsthemes' ), get_comment_date() ) ?></time>
				</div>
				<div class="comment-text entry-content">
					<?php comment_text(); ?>
				</div>
				<?php 
				comment_reply_link( 
					array_merge( 
						$args, 
						array(
								'reply_text' => __( 'Reply', 'colabsthemes' ),
								'depth' => $depth,
								'max_depth' => $args['max_depth']
						) 
					) 
				); 
				?>
			</div>
		</div>
  
<?php }


// Produces an avatar image with the hCard-compliant photo class
function commenter_link() {
 $commenter = get_comment_author_link();
 if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
  $commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
 } else {
  $commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
 }
 $avatar_email = get_comment_author_email();
 $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 64 ) );
 echo $avatar;
} // end commenter_link

// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
        ?>
      <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
       <div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s', 'colabsthemes'),
         get_comment_author_link(),
         get_comment_date(),
         get_comment_time() );
         edit_comment_link(__('Edit', 'colabsthemes'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'colabsthemes') ?>
            <div class="comment-content">
       <?php comment_text() ?>
   </div>
<?php } // end custom_pings

function colabs_list_comments_front($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
?>

	<li <?php comment_class(); ?>>
		<div id="comment-<?php comment_ID(); ?>" class="comment-entry">
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'colabsthemes' ); ?></em>
			<?php endif; ?>

			<div class="comment-author">
			<?php commenter_link() ?>
			</div>

			<div class="comment-content">
        <p>
          <span class="author-name"><?php echo get_comment_author_link(); ?></span> 
          <span class="comment-meta"><?php printf( __( '%1$s', 'colabsthemes' ), get_comment_date() ) ?></span>
        </p>

				<?php comment_text(); ?>
			</div>
			<?php echo colabs_get_comment_reply_link( array_merge( $args, array(
					'reply_text' => __( 'Reply', 'colabsthemes' ),
					'depth' => $depth,
					'max_depth' => $args['max_depth']
				) ) ); ?><br />
		</div>
  
<?php }
function colabs_get_comment_reply_link($args = array(), $comment = null, $post = null) {
	        global $user_ID;
	
	        $defaults = array('add_below' => 'comment', 'respond_id' => 'respond', 'reply_text' => __('Reply','colabsthemes'),
	                'login_text' => __('Log in to Reply','colabsthemes'), 'depth' => 0, 'before' => '', 'after' => '');
	
	        $args = wp_parse_args($args, $defaults);
	
	        if ( 0 == $args['depth'] || $args['max_depth'] <= $args['depth'] )
	                return;
	
	        extract($args, EXTR_SKIP);
	
	        $comment = get_comment($comment);
	        if ( empty($post) )
	                $post = $comment->comment_post_ID;
	        $post = get_post($post);
	
	        if ( !comments_open($post->ID) )
	                return false;
	
	        $link = '';
	
         if ( get_option('comment_registration') && !$user_ID )
	                $link = '<a rel="nofollow" class="comment-reply-login" href="' . esc_url( wp_login_url( get_permalink() ) ) . '">' . $login_text . '</a>';
	        else
	                $link = "<a class='comment-reply-link' href='". get_permalink($post->ID)."?replytocom".$comment->comment_ID . "#" . $respond_id . "' onclick='return addComment.moveForm(\"$add_below-$comment->comment_ID\", \"$comment->comment_ID\", \"$respond_id\", \"$post->ID\")'>$reply_text</a>";
	        return apply_filters('comment_reply_link', $before . $link . $after, $args, $comment, $post);
	}
?>