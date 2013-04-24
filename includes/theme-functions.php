<?php 

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Add metabox style page template
- colabs_pinterest_get_rss_feed
- Excerpt
- Page navigation
- CoLabsTabs - Popular Posts
- CoLabsTabs - Latest Posts
- CoLabsTabs - Latest Comments
- WordPress 3.0 New Features Support
- using_ie - Check IE
- post-thumbnail - WP 3.0 post thumbnails compatibility
- automatic-feed-links Features
- Twitter button - twitter
- Facebook Like Button - fblike
- Facebook Share Button - fbshare
- Google +1 Button - [google_plusone]
-- Load Javascript for Google +1 Button
- colabs_link - Alternate Link & RSS URL
- Open Graph Meta Function
- colabs_share - Twitter, FB & Google +1
- WordPress Customizer
- Post Meta
- Count Like

-----------------------------------------------------------------------------------*/

/* add support custom background*/
add_custom_background();
	  
/*-----------------------------------------------------------------------------------*/
/*  Add metabox style page template */
/*-----------------------------------------------------------------------------------*/
add_action("admin_init", "themes_admin_init_colabs_page_style");
	  
	function themes_admin_init_colabs_page_style(){
		add_meta_box("add_themes_meta_colabs_page_style", "Page Gallery Style", "add_themes_meta_colabs_page_style", "page", "side", "high");
	}
	
	function add_themes_meta_colabs_page_style() {
		global $post;
		$meta_style_gallery = get_post_meta($post->ID, "meta_style_gallery",true);
	?>
		<style type="text/css"> 
			#add_themes_meta_colabs_page_style h4 { float: left }
			#add_themes_meta_colabs_page_style .select-gallery { width:90%; }
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				colabs_load_style('#page_template');
				changeSelect();
			});
			
			jQuery('#page_template').live('change', function(){
				colabs_load_style(this);
			});
			
			function colabs_load_style(el){
				if(jQuery(el).val()=='template-gallery.php') {
					jQuery('#add_themes_meta_colabs_page_style').show();
				}else{
					jQuery('#add_themes_meta_colabs_page_style').hide();
				}
			}
			
			jQuery('#meta_style_gallery').live('change', function(){
				changeSelect();
			});
			
			function changeSelect() {
				if( jQuery('#meta_style_gallery').val() == "facebook" ) {
					jQuery('#facebook_gallery_block').show();
				} else {
					jQuery('#facebook_gallery_block').hide();
				}
			}
			
		</script>
		
		
		<div class="gallery-metabox">
			<h4><?php _e( 'Select Source Gallery:', 'colabsthemes' ); ?></h4>
			<select name="meta_style_gallery" id="meta_style_gallery" class="select-gallery">
		
				<option <?php if($meta_style_gallery=='post')echo 'selected="selected"';?> value="post">Post</option>
				<option <?php if($meta_style_gallery=='photograph')echo 'selected="selected"';?> value="photograph">Photograph</option>
				<option <?php if($meta_style_gallery=='pinterest')echo 'selected="selected"';?> value="pinterest">Pinterest</option>
				<option <?php if($meta_style_gallery=='instagram')echo 'selected="selected"';?> value="instagram">Instagram</option>
				<option <?php if($meta_style_gallery=='flickr')echo 'selected="selected"';?> value="flickr">Flickr</option>
				<option <?php if($meta_style_gallery=='picasa')echo 'selected="selected"';?> value="picasa">Picasa</option>
				<option <?php if($meta_style_gallery=='facebook')echo 'selected="selected"';?> value="facebook">Facebook</option>

			</select>
			<?php
			$session_key= get_option('fb-session-key');
			$session_sec= get_option('fb-session-secret');
			if(($session_key!='')&&($session_sec!='')){
			require_once('facebook-platform/facebook.php');
			global $appapikey,$appsecret;
			$facebook = new Facebook($appapikey, $appsecret, null, true);
			$uid = get_option('fb-session-uid');
			$facebook->api_client->session_key = $session_key;
			$facebook->api_client->secret      = $session_sec;
			$albums = $facebook->api_client->photos_getAlbums($uid, null);
			if( is_array($albums) ){
			?>
			<div id="facebook_gallery_block" style="display:none">
				<h4><?php _e( 'Select Facebook Gallery:', 'colabsthemes' ); ?></h4>
				<select name="facebook_gallery_id" id="facebook_gallery_id" class="select-gallery">
				<?php
					$selected = '';
					$facebook_gallery_id = get_post_meta($post->ID, "facebook_gallery_id",true);
					foreach($albums as $album){
					if($facebook_gallery_id==$album['aid'])$selected = 'selected="selected"';
					echo '<option '.$selected.' value="'.$album['aid'].'">'.$album['name'].'</option>';
					$selected = '';
					}
				?>
				</select>
			</div>
			<?php }}?>
		</div>
		
		<?php
	}
	
	add_action('save_post', 'themes_meta_save_details_colabs_page_style');
	  
	  function themes_meta_save_details_colabs_page_style(){
		  global $post;
			update_post_meta($post->ID, "meta_style_gallery", $_POST['meta_style_gallery']);	
			$session_key= get_option('fb-session-key');
			$session_sec= get_option('fb-session-secret');
			if(($session_key!='')&&($session_sec!='')){	
			update_post_meta($post->ID, "facebook_gallery_id", $_POST['facebook_gallery_id']);
			}
	  }




/*-----------------------------------------------------------------------------------*/
/*  colabs_pinterest_get_rss_feed */
/*-----------------------------------------------------------------------------------*/
if ( !function_exists('colabs_pinterest_get_rss_feed') ) {
	function colabs_pinterest_get_rss_feed( $pinterest_username, $number_of_pins_to_show, $feed_url ){				
		// Get a SimplePie feed object from the specified feed source.		
		$rss = fetch_feed( $feed_url );
		if (!is_wp_error( $rss ) ) : 
			// Figure out how many total items there are, but limit it to number specified
			$maxitems = $rss->get_item_quantity( $number_of_pins_to_show ); 
			$rss_items = $rss->get_items( 0, $maxitems ); 
		endif;		
		return $rss_items;
	}
}

/*-----------------------------------------------------------------------------------*/
/*  Menu Navigator */
/*-----------------------------------------------------------------------------------*/
if ( function_exists('register_nav_menus') ) {
	add_theme_support( 'nav-menus' );
    register_nav_menus( array(
        'primary' => __( 'Main Menu','colabsthemes' )
	));    
}

if (!function_exists('colabs_nav_fallback')) {
function colabs_nav_fallback($div_id){
    if (is_array($div_id)){ $div_id = $div_id['theme_location']; }
    if ( $div_id == 'primary' ){
        wp_page_menu('depth=0&title_li=');
    }
}}

function replace_menu_div( $newel ) {
	$html4nav = array();
	$html4nav[0] = '/<div class="menu">/';
	$html4nav[1] = '/<\/div>/';
	$html5nav = array();
	$html5nav[0] = '<nav class="top-nav">';
	$html5nav[1] = '</nav>';
	ksort($html4nav);
	ksort($html5nav);
	return preg_replace($html4nav, $html5nav, $newel, 1);
	}
add_filter('wp_page_menu','replace_menu_div');
/*-----------------------------------------------------------------------------------*/
/* SET GLOBAL CoLabs VARIABLES
/*-----------------------------------------------------------------------------------*/

// Slider Tags
	$GLOBALS['slide_tags_array'] = array();
// Duplicate posts 
	$GLOBALS['shownposts'] = array();

/*-----------------------------------------------------------------------------------*/
/* Excerpt
/*-----------------------------------------------------------------------------------*/

//Add excerpt on pages
if(function_exists('add_post_type_support'))
add_post_type_support('page', 'excerpt');

/** Excerpt character limit */
/* Excerpt length */
function colabs_excerpt_length($length) {
if( get_option('colabs_excerpt_length') != '' ){
        return get_option('colabs_excerpt_length');
    }else{
        return 45;
    }
}
add_filter('excerpt_length', 'colabs_excerpt_length');

//Custom Excerpt Function
function colabs_custom_excerpt($limit,$more) {
	global $post;
	if ($limit=='')$limit=35;
	$print_excerpt = '<p>';
	$output = $post->post_excerpt;
	if ($output!=''){
	$print_excerpt .= $output;
	}else{
	$content = get_the_content('');
	$content = strip_shortcodes( $content );
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content);	
	$excerpt = explode(' ',$content, $limit);
	array_pop($excerpt);
	$print_excerpt .= implode(" ",$excerpt).$more;
	}
	$print_excerpt .= '</p>';
	echo $print_excerpt;
}

/*-----------------------------------------------------------------------------------*/
/* Page navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_pagenav')) {
	function colabs_pagenav() {

		global $colabs_options, $wp_query, $paged, $page;

		// If the user has set the option to use simple paging links, display those. By default, display the pagination.
		if ( @$colabs_options['colabs_pagination_type'] == 'simple' ) {

        if (is_home() || is_archive()){ ?>
		  <p id="page">
			<strong class="page-prev"><?php previous_posts_link('Previous posts') ?></strong>
		  <!--a href="#">Full Archives</a-->
			<strong class="page-next"><?php next_posts_link('Next posts','') ?></strong>
		  </p><!--/#page-->
        <?php } else
			if ( get_next_posts_link() || get_previous_posts_link() ) {
		?>
            <div class="nav-entries">
                <?php next_posts_link( '<span class="nav-prev fl">'. __( '<span class="meta-nav">&larr;</span> Older posts', 'colabsthemes' ) . '</span>' ); ?>
                <?php previous_posts_link( '<span class="nav-next fr">'. __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'colabsthemes' ) . '</span>' ); ?>
                <div class="clear"></div>
            </div><!--/.nav-entries-->
		<?php
			} // End IF Statement

		} else {

			colabs_pagination();

		} // End IF Statement

	} // End colabs_pagenav()
} // End IF Statement   

if (!function_exists('colabs_postnav')) {
	function colabs_postnav() {
		?>
    <div class="navigation">
        <div class="navleft fl"><?php next_post_link('%link','&laquo; Prev') ;?></div>
        <div class="navright fr"><?php previous_post_link('%link','Next &raquo;'); ?></div>
        
    </div><!--/.navigation-->
		<?php 
	}
}




/*-----------------------------------------------------------------------------------*/
/* using_ie - Check IE */
/*-----------------------------------------------------------------------------------*/
//check IE
function using_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;    
}

/*-----------------------------------------------------------------------------------*/
/*  automatic-feed-links Features  */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) && get_option('colabs_feedlinkurl') == '' ) {
add_theme_support( 'automatic-feed-links' );
}

/*-----------------------------------------------------------------------------------*/
/* colabs_link - Alternate Link & RSS URL */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_head', 'colabs_link' );
if (!function_exists('colabs_link')) {
function colabs_link(){ 
?>	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('colabs_feedlinkurl') ) { echo get_option('colabs_feedlinkurl'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	
<?php 
}}

/*-----------------------------------------------------------------------------------*/
/*  Open Graph Meta Function    */
/*-----------------------------------------------------------------------------------*/
function colabs_meta_head(){
    do_action( 'colabs_meta' );
}
add_action( 'colabs_meta', 'og_meta' );  
	
/*-----------------------------------------------------------------------------------*/	
/* Search Form*/
/*-----------------------------------------------------------------------------------*/
function custom_search( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <input type="text" placeholder="'.__("Search","colabsthemes").'" value="' . get_search_query() . '" name="s" id="s" />
	<input type="submit" value="'.__("Search","colabsthemes").'" class="btn"/>
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'custom_search' );

/*-----------------------------------------------------------------------------------*/
/* CoLabs - Footer Credit */
/*-----------------------------------------------------------------------------------*/
function colabs_credit(){
global $themename,$colabs_options;

if( $colabs_options['colabs_footer_credit'] != 'true' ) 
	echo '<p>&copy; 2012 <a href="http://colorlabsproject.com/themes/'.get_option('colabs_themename').'">'.get_option('colabs_themename').'</a>. All Rights Reserved</p>';
else 
	echo stripslashes( $colabs_options['colabs_footer_credit_txt'] ); 
 
}


/*-----------------------------------------------------------------------------------*/
/*  colabs_share - Twitter, FB & Google +1    */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'colabs_share' ) ) {
function colabs_share() {
    
$return = '';


$colabs_share_twitter = get_option('colabs_share_twitter');
$colabs_share_fblike = get_option('colabs_share_fblike');
$colabs_share_google_plusone = get_option('colabs_share_google_plusone');
$colabs_share_pinterest = get_option('colabs_share_pinterest');
$colabs_share_linkedin = get_option('colabs_share_linkedin');

    //Share Button Functions 
    global $colabs_options;
    $url = get_permalink();
    $share = '';
    
    //Twitter Share Button
    if(function_exists('colabs_shortcode_twitter') && $colabs_share_twitter == "true"){
        $tweet_args = array(  'url' => $url,
   							'style' => 'horizontal',
   							'source' => ( $colabs_options['colabs_twitter_username'] )? $colabs_options['colabs_twitter_username'] : '',
   							'text' => '',
   							'related' => '',
   							'lang' => '',
   							'float' => 'fl'
                        );

        $share .= colabs_shortcode_twitter($tweet_args);
    }
    
   
        
    //Google +1 Share Button
    if( function_exists('colabs_shortcode_google_plusone') && $colabs_share_google_plusone == "true"){
        $google_args = array(
						'size' => 'medium',
						'language' => '',
						'count' => '',
						'href' => $url,
						'callback' => '',
						'annotation' => 'bubble',
						'float' => 'left'
					);        

        $share .= colabs_shortcode_google_plusone($google_args);       
    }
	
	 //Facebook Like Button
    if(function_exists('colabs_shortcode_fblike') && $colabs_share_fblike == "true"){
    $fblike_args = 
    array(	
        'float' => 'left',
        'url' => '',
        'style' => 'button_count',
        'showfaces' => 'false',
        'width' => '82',
        'height' => '',
        'verb' => 'like',
        'colorscheme' => 'light',
        'font' => 'arial'
        );
        $share .= colabs_shortcode_fblike($fblike_args);    
    }
	 
	global $post;
	if (is_attachment()){
	$att_image = wp_get_attachment_image_src( $post->id, "thumbnail");
	$image = $att_image[0];
	}else{
    $image = colabs_image('return=true&link=url&id='.$post->ID);
	}
	//Pinterest Share Button
    if( function_exists('colabs_shortcode_pinterest') && $colabs_share_pinterest == "true"){
        $pinterest_args = array(
						'count' => 'horizontal',
						'float' => 'left',  
						'use_post' => 'true',
						'image_url' => $image,
						'url' => $url
					);        

        $share .= colabs_shortcode_pinterest($pinterest_args);       
    }
	
	//Linked Share Button
    if( function_exists('colabs_shortcode_linkedin_share') && $colabs_share_linkedin == "true"){
        $linkedin_args = array(
						'style' => 'right', 
						'float' => 'left'
					);        

        $share .= colabs_shortcode_linkedin_share($linkedin_args);       
    }
	
    $return .= '<div class="social_share">'.$share.'</div><div class="clear"></div>';
    
    return $return;
}
}


/*-----------------------------------------------------------------------------------*/
/*  colabs_social_net - Add social network icon */
/*-----------------------------------------------------------------------------------*/
if(!function_exists('colabs_social_net')){
function colabs_social_net($class){
?>
    
      <div class="soc-net <?php if($class) echo $class; ?>">

		<a class="rss" href="<?php if(get_option("colabs_feed_url") != ''){ echo 'http://feeds.feedburner.com/'.get_option("colabs_feed_url");	}else{ bloginfo("rss2_url"); }?>">
		<i class="icon-rss"></i></a>
		
		<?php if (get_option("colabs_social_facebook")!='') : ?>
    	<a class="facebook" href="<?php echo get_option("colabs_social_facebook"); ?>"><i class="icon-facebook"></i></a>
    	<?php endif; ?>

    	<?php if (get_option("colabs_social_twitter")!='') : ?>
    	<a class="twitter" href="<?php echo get_option("colabs_social_twitter"); ?>"><i class="icon-twitter"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_google") != '' ) : ?>
    	<a class="google" href="<?php echo get_option("colabs_social_google");?>"><i class="icon-google"></i></a>
    	<?php endif; ?>
        
		<?php if (get_option("colabs_social_picasa") != '' ) : ?>
    	<a class="picasa" href="<?php echo get_option("colabs_social_picasa");?>"><i class="icon-picasa"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_youtube") != '' ) : ?>
    	<a class="vimeo" href="<?php echo get_option("colabs_social_youtube");?>"><i class="icon-vimeo"></i></a>
    	<?php endif; ?>
    	
		<?php if (get_option("colabs_social_flickr") != '' ) : ?>
    	<a class="flickr" href="<?php echo get_option("colabs_social_flickr");?>"><i class="icon-flickr"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_linked") != '' ) : ?>
    	<a class="linked" href="<?php echo get_option("colabs_social_linked");?>"><i class="icon-linkedin"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_delicious") != '' ) : ?>
    	<a class="deli" href="<?php echo get_option("colabs_social_delicious");?>"><i class="icon-delicious-sign"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_lastfm") != '' ) : ?>
    	<a class="lastfm" href="<?php echo get_option("colabs_social_lastfm");?>"><i class="icon-lastfm"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_stumbleupon") != '' ) : ?>
    	<a class="stumble" href="<?php echo get_option("colabs_social_stumbleupon");?>"><i class="icon-stumble"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_techno") != '' ) : ?>
    	<a class="techno" href="<?php echo get_option("colabs_social_techno");?>"><i class="icon-techno"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_wordpress") != '' ) : ?>
    	<a class="social-wp" href="<?php echo get_option("colabs_social_wordpress");?>"><i class="icon-wordpress"></i></a>
    	<?php endif; ?>
		
		<?php if (get_option("colabs_social_yahoo") != '' ) : ?>
    	<a class="yahoo" href="<?php echo get_option("colabs_social_yahoo");?>"><i class="icon-yahoo"></i></a>
    	<?php endif; ?>
      </div>
    
<?php
}}

/*-----------------------------------------------------------------------------------*/
/* WordPress Customizer
/*-----------------------------------------------------------------------------------*/
function colabs_customize_register( $wp_customize ) {
  $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
  $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

  //Access the WordPress Categories via an Array
  $colabs_categories = array();  
  $colabs_categories_obj = get_categories('hide_empty=0');
  foreach ($colabs_categories_obj as $colabs_cat) {
      $colabs_categories[$colabs_cat->cat_ID] = $colabs_cat->cat_name;}


  // Logo Settings
  // -------------
  $wp_customize->add_section( 'logo_settings', array(
    'title'    => __( 'Logo Settings', 'colabsthemes' ),
    'priority' => 50,
  ) );

  $wp_customize->add_setting( 'colabs_logo', array(
    'type'        => 'option',
    'capability'  => 'manage_options',
  ) );

  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'colabs_logo', array(
    'label'    => __( 'Header Logo', 'colabsthemes' ),
    'section'  => 'logo_settings',
    'settings' => 'colabs_logo',
    'priority' => 1,
  ) ) );

}
add_action( 'customize_register', 'colabs_customize_register' );

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 * Used with blogname and blogdescription.
 *
 */
function colabs_customize_preview_js() {
  wp_enqueue_script( 'colabs-customizer', get_template_directory_uri() . '/includes/js/theme-customizer.js', array( 'customize-preview' ), '20120620', true );
}
add_action( 'customize_preview_init', 'colabs_customize_preview_js' );


/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_popular')) {
	function colabs_tabs_popular( $posts = 5, $size = 35 ) {
		global $post;
		$popular = get_posts('caller_get_posts=1&orderby=comment_count&showposts='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) colabs_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?>
		<div class="tabs-content">
			<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
			<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
			<div class="clear"></div>
		</div>
	</li>
	<?php endforeach;
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Latest Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_latest')) {
	function colabs_tabs_latest( $posts = 5, $size = 35 ) {
		global $post;
		$latest = get_posts('caller_get_posts=1&showposts='. $posts .'&orderby=post_date&order=desc');
		foreach($latest as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) colabs_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?>
		<div class="tabs-content">
			<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
			<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
			<div class="clear"></div>
		</div>
	</li>
	<?php endforeach; 
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Latest Comments */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_comments')) {
	function colabs_tabs_comments( $posts = 5, $size = 35 ) {
		global $wpdb;
		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
		comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
		comment_type,comment_author_url,
		SUBSTRING(comment_content,1,50) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
		$wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND
		post_password = ''
		ORDER BY comment_date_gmt DESC LIMIT ".$posts;
		
		$comments = $wpdb->get_results($sql);
		
		foreach ($comments as $comment) {
		?>
		<li>
            <?php $comm_link = get_permalink($comment->ID) .'#comment-'. $comment->comment_ID; 
            $comm_author_link = $comment->comment_author_url;
            ?>
            <?php if( $comm_author_link ){ ?><a href="<?php echo $comm_author_link; ?>"><?php } ?>
                <?php echo get_avatar( $comment, $size ); ?>
            <?php if( $comm_author_link ){ ?></a><?php } ?>
		
			<div>
      <a href="<?php if( $comm_author_link ){ echo $comm_author_link; }else{ echo $comm_link; }?>" title="<?php _e('on ', 'colabsthemes'); ?> <?php echo $comment->post_title; ?>">
                <span class="author"><?php echo strip_tags($comment->comment_author); ?></span></a>: <a href="<?php echo $comm_link; ?>" title="<?php _e('on ', 'colabsthemes'); ?> <?php echo $comment->post_title; ?>"><span class="comment"><?php echo strip_tags($comment->com_excerpt); ?>...</span></a>
      </div>
			
			<div class="clear"></div>
		</li>
		<?php 
		}
	}
}

/* Post Meta */
if (!function_exists('colabs_post_meta')) {
	function colabs_post_meta(){?>
		<ul class="entry-meta">
			<li class="entry-author icon-pencil"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta('display_name'); ?></a></li>
			<?php if(get_post_type(get_the_ID())!='page'){ ?>
            <li class="entry-category icon-tags"><?php the_category(', '); if(get_post_type(get_the_ID())=='photograph') echo get_the_term_list( get_the_ID(), 'photograph-categories', '', ', ', '' );?></li>
			<?php } ?>
			<?php if(comments_open()){ ?>
            <li class="entry-comments-count icon-comment"><a href="<?php comments_link(); ?>"><?php comments_number( __('Add Comment','colabsthemes'), __('1 Comment','colabsthemes'), __('% Comments','colabsthemes') ); ?></a></li>
			<?php } ?>
			<?php if(get_post_type(get_the_ID())!='page'){ ?>
            <li class="entry-date"><span class="days"><?php the_time( "d" ); ?> </span><span class="month"><?php the_time( "M" ); ?> </span></li>
			<?php } ?>
        </ul>
	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/*  Count Like */
/*-----------------------------------------------------------------------------------*/
/**
 * Get like data
 * @param  {int} $id Id of the posr
 * @return {string} 
 */
function get_like( $id ) {
  $total_likes = get_post_meta($id, 'total_likes', true) + 0;
  return $total_likes;
}

/**
 * Create post meta like
 * @param  {int} $id post id
 */
function create_like( $id, $votes = 0, $total = 0) {
  $rate_meta = 	array(
					array(
						'meta_key'    => 'total_likes',
						'meta_value'  => get_post_meta( $id, 'total_likes', true )
					)
				);

  foreach( $rate_meta as $meta ) {
    add_post_meta( $id, $meta['meta_key'], $meta['meta_value']+1, true ) or update_post_meta( $id, $meta['meta_key'], $meta['meta_value']+1);
  }
}
?>
