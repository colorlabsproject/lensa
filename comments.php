<div id="comments">
	<?php if ( have_comments() ) : ?>
		<h3 class="comment-header">
			<?php printf( _n( '<span class="comment-count">%1$s Comment</span>', '<span class="comment-count">%1$s Comments</span>', get_comments_number(), 'colabsthemes' ), number_format_i18n( get_comments_number()) ); ?>
			<a href="#respond"><?php _e("Add Yours","colabsthemes"); ?></a> 
		</h3>
		

		<ol class="commentlist">
			<?php wp_list_comments( array(
				'type'			=> 'comment',
				'callback' 	=> 'colabs_list_comments',
				'max_depth'	=> 2
			) ); ?>
		</ol>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h3 class="comment-header"><?php _e( 'Comment navigation', 'colabsthemes' ); ?></h3>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'colabsthemes' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'colabsthemes' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>
	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'colabsthemes' ); ?></p>
	<?php endif; ?>

	<?php //comment_form(); ?>

	<?php
	// Custom comment form
	$fields = array(
		'author'	=> '<p >
                  <label for="author">'. __('Your Name (required)', 'colabsthemes') .'</label>
									<input id="author" name="author" type="text" placeholder="'.__('Name','colabsthemes').'" value="' . esc_attr( $commenter['comment_author'] ) . '" size=""' . $aria_req . ' />' . ( $req ? '' : '' ).'</p>',
		'email'		=> '<p>
                  <label for="email">'. __('Your Email (required)', 'colabsthemes') .'</label>
									<input id="email" name="email" type="text" placeholder="'.__('Email','colabsthemes').'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size=""' . $aria_req . ' />' . ( $req ? '' : '' ).'</p>',
		'url'			=> '<p>
                  <label for="url">'. __('Your Website', 'colabsthemes') .'</label>
									<input id="url" name="url" type="text" placeholder="'.__('Website','colabsthemes').'" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="" />
									</p>'
	);

	comment_form(array(
		'comment_field'        => '<p class="comment-form-comment"><label for="comment">'.__('Comment', 'colabsthemes').'</label><textarea placeholder="'.__('Type your comment here','colabsthemes').'" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'title_reply'          => __( 'Leave a comment','colabsthemes' ),
		'title_reply_to'       => __( '' ),
		'label_submit'         => __( 'Post Comment' ,'colabsthemes'),
		'cancel_reply_link'    => __( 'Cancel' ,'colabsthemes'),
	));
	?>
	
</div><!-- #comments -->
