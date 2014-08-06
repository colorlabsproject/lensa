<?php
global $user_photo;

$api_key = get_option( 'colabs_api_flickr' );
$api_secret = get_option( 'colabs_secret_flickr' );
$user_id = get_option('colabs_username_flickr');
$flickr = new Colabs_WP_Flickr( $api_key, $api_secret );
$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$user_photo = $flickr->get_user_photos( $user_id, get_option( 'colabs_piccount_flickr' ), $page );

foreach( $user_photo['photos'] as $photo ) {
	$date = date(get_option('date_format'), $photo['dateuploaded'] );
	echo '<li class="gallery-item" id="flickr-photo-'. $photo['id'] .'">';
		echo '<a href="'. $photo['image']['o'] .'" title="'. $photo['title'] .'" rel="lightbox" data-url="'. $photo['page_url'] .'">';
			echo '<img src="'. $photo['image']['m'] .'">';
		echo '</a>';

		echo 
			'<div class="like">
				<p class="entry-likes">
					<span>'.$photo['views'].' </span> 
					<i class="icon-heart"></i> 
				</p>
			</div>
			<div class="time">
				<p class="entry-time">
					<i class="icon-time"></i> 
					<span>'.$date.'</span> 
				</p>
			</div>';

	echo '</li>';
}