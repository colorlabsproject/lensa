<?php

//Enable CoLabsSEO on these custom Post types
//$seo_post_types = array('post','page');
//define("SEOPOSTTYPES", serialize($seo_post_types));

//Global options setup
add_action('init','colabs_global_options');
function colabs_global_options(){
	// Populate CoLabsThemes option in array for use in theme
	global $colabs_options;
	$colabs_options = get_option('colabs_options');
}

add_action('admin_head','colabs_options');  
if (!function_exists('colabs_options')) {
function colabs_options(){
	
// VARIABLES
$themename = "Lensa";
$manualurl = 'http://colorlabsproject.com';
$shortname = "colabs";

	
//More Options
$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");

$other_entries_10 = array("Select a number:","1","2","3","4","5","6","7","8","9","10");

$other_entries_4 = array("Select a number:","1","2","3","4");

$other_entries_5 = array("1","2","3","4","5");

$other_entries_65 = array("Select a number:","10","15","20","25","30","35","40","45","50","55","60","65");

// THIS IS THE DIFFERENT FIELDS
$options = array();

// General Settings
$options[] = array( "name" => __("General Settings","colabsthemes"),
					"type" => "heading",
					"icon" => "general");			
					
$options[] = array( "name" => __( "Use for blog title/logo", "colabsthemes" ),
					"desc" => __( "Select title or logo for your blog.", "colabsthemes" ),
					"id" => $shortname."_logotitle",
					"std" => "logo",
					"type" => "select2",
					"options" => array( "logo" => __( "Logo", "colabsthemes" ), "title" => __( "Title", "colabsthemes" ) ) );
                    
$options[] = array( "name" => __("Custom Favicon","colabsthemes"),
					"desc" => __("Upload a 16x16px ico image that will represent your website's favicon. Favicon/bookmark icon will be shown at the left of your blog's address in visitor's internet browsers.","colabsthemes"),
					"id" => $shortname."_custom_favicon",
					"std" => trailingslashit( get_bloginfo('template_url') ) . "images/favicon.png",
					"type" => "upload"); 

$options[] = array( "name" => __("Header Custom Logo","colabsthemes"),
					"desc" => __("Upload a logo for your theme, or specify an image URL directly. Best image size in 219x48 px","colabsthemes"),
					"id" => $shortname."_logo",
					"std" => trailingslashit( get_bloginfo('template_url') ) . "images/logo.png",
					"type" => "upload");

$options[] = array( "name" => "Disable Responsive",
					"desc" => "You can disable responsive module for your site.",
					"id" => $shortname."_disable_mobile",
					"std" => "false",
					"type" => "checkbox");
					
// Posts FrontPage Options						
$options[] = array( "name" => "FrontPage  Settings",
					"type" => "heading",
					"icon" => "home");	
					
$options[] = array( "name" => __( "Slideshow on/off", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_slideshow",
					"std" => "1",
					"type" => "select2",
					"options" => array( "1" => __( "True", "colabsthemes" ), "0" => __( "False", "colabsthemes" ) ) );
					
$options[] = array( "name" => __( "Slideshow starts playing automatically", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_autoplay",
					"std" => "1",
					"type" => "select2",
					"options" => array( "1" => __( "True", "colabsthemes" ), "0" => __( "False", "colabsthemes" ) ) );
					
$options[] = array( "name" => __( "Pauses slideshow on last slide", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_stop_loop",
					"std" => "0",
					"type" => "select2",
					"options" => array( "1" => __( "True", "colabsthemes" ), "0" => __( "False", "colabsthemes" ) ) );			
									
$options[] = array( "name" => __( "Length between transitions", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_slide_interval",
					"std" => "6000",
					"class" => "",
					"type" => "text");
					
$options[] = array( "name" => __( "Transition", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_transition",
					"std" => "1",
					"type" => "select2",
					"options" => array( "0" => __( "None", "colabsthemes" ), 
										"1" => __( "Fade", "colabsthemes" ), 
										"2" => __( "Slide Top", "colabsthemes" ), 
										"3" => __( "Slide Right", "colabsthemes" ), 
										"4" => __( "Slide Bottom", "colabsthemes" ), 
										"5" => __( "Slide Left", "colabsthemes" ), 
										"6" => __( "Carousel Right", "colabsthemes" ), 
										"7" => __( "Carousel Left", "colabsthemes" )
										) 
					);		
									
$options[] = array( "name" => __( "Speed of transition", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_transition_speed",
					"std" => "2000",
					"class" => "",
					"type" => "text");
					
$options[] = array( "name" => __( "Pause slideshow on hover", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_pause_hover",
					"std" => "0",
					"type" => "select2",
					"options" => array( "1" => __( "True", "colabsthemes" ), "0" => __( "False", "colabsthemes" ) ) );	
					
$options[] = array( "name" => __( "Keyboard navigation on/off", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_keyboard_nav",
					"std" => "1",
					"type" => "select2",
					"options" => array( "1" => __( "True", "colabsthemes" ), "0" => __( "False", "colabsthemes" ) ) );
					
$options[] = array( "name" => __( "Performance ", "colabsthemes" ),
					"desc" => __( "(Only works for Firefox/IE, not Webkit)", "colabsthemes" ),
					"id" => $shortname."_performance",
					"std" => "1",
					"type" => "select2",
					"options" => array( "0" => __( "Normal", "colabsthemes" ), 
										"1" => __( "Hybrid speed/quality", "colabsthemes" ), 
										"2" => __( "Optimizes image quality", "colabsthemes" ), 
										"3" => __( "Optimizes transition speed ", "colabsthemes" )
										) 
					);		
					
$options[] = array( "name" => __( "Disables image dragging and right click with Javascript", "colabsthemes" ),
					"desc" => __( "", "colabsthemes" ),
					"id" => $shortname."_image_protect",
					"std" => "1",
					"type" => "select2",
					"options" => array( "1" => __( "True", "colabsthemes" ), "0" => __( "False", "colabsthemes" ) ) );

					
// Instagram FrontPage Options						
$options[] = array( "name" => "Instagram Settings",
					"type" => "heading",
					"icon" => "home");		 			
				
$options[] = array( "name" => __("Type", "colabsthemes" ),
					"desc" => __("Select type to show the instagram", "colabsthemes" ),
					"id" => $shortname."_type_instagram",
					"type" => "select2",
                    "class" => "",
					"options" => array("" => "Popular","self" => "Self", "myfeed" => "Feed" ) );					
									
$options[] = array( "name" => __( "Count Instagram", "colabsthemes" ),
					"desc" => __( "Count of your Instagram image.", "colabsthemes" ),
					"id" => $shortname."_piccount_instagram",
					"std" => "10",
					"class" => "",
					"type" => "text");
					
$options[] = array( "name" => __( 'Random Instagram', 'colabsthemes' ),
					"desc" => __( 'Random instagram image.', 'colabsthemes' ),
					"id" => $shortname."_random_instagram",
					"class" => "",
					"type" => "checkbox");
					
$options[] = array( "name" => __("Tag Instagram (optional)", "colabsthemes" ),
					"desc" => __("Tag (Currently only one tag. Username is ignored.)", "colabsthemes" ),
					"id" => $shortname."_tag_instagram",
					"class" => "",
					"type" => "text");
				
$options[] = array( "name" => __("Address/Coordinates Instagram (optional)", "colabsthemes" ),
					"desc" => __("Entry Address/Coordinates instagram in here.", "colabsthemes" ),
					"id" => $shortname."_address_instagram",
					"class" => "",
					"type" => "text");			
		
// Flickr FrontPage Options	
$options[] = array( "name" => "Flickr Settings",
					"type" => "heading",
					"icon" => "home");							
												
$options[] = array( "name" => __("Flickr API", "colabsthemes" ),
					"desc" => __("Entry Flickr API.", "colabsthemes" ),
					"id" => $shortname."_api_flickr",
					"std" => "ac87048a9c9f196051db45de49f3830a",
					"type" => "text");					
												
$options[] = array( "name" => __("Flickr Secret", "colabsthemes" ),
					"desc" => __("Entry Flickr Secret.", "colabsthemes" ),
					"id" => $shortname."_secret_flickr",
					"std" => "79e03f86fd898330",
					"type" => "text");				
												
$options[] = array( "name" => __("Flickr ID", "colabsthemes" ),
					"desc" => __("Entry Flickr ID (<a href=\"http://www.idgettr.com\" target=\"_blank\">idGettr</a>) in here.", "colabsthemes" ),
					"id" => $shortname."_username_flickr",
					"class" => "",
					"type" => "text");
					
$options[] = array( "name" => __( "Count Flickr", "colabsthemes" ),
					"desc" => __( "Entry the limit for Flickr.", "colabsthemes" ),
					"id" => $shortname."_piccount_flickr",
					"std" => "10",
					"class" => "",
					"type" => "text");
		
// Picasa FrontPage Options	
$options[] = array( "name" => "Picasa Settings",
					"type" => "heading",
					"icon" => "home");	
					
$options[] = array( "name" => __("Picasa Username", "colabsthemes" ),
					"desc" => __("Entry Picasa Username in here.", "colabsthemes" ),
					"id" => $shortname."_username_picasa",
					"std" => "113539730014413629030",
					"type" => "text");
					
$options[] = array( "name" => __( "Count Picasa", "colabsthemes" ),
					"desc" => __( "Entry the limit for Picasa.", "colabsthemes" ),
					"id" => $shortname."_piccount_picasa",
					"std" => "10",
					"class" => "",
					"type" => "text");	
		
// Pinterest FrontPage Options	
$options[] = array( "name" => "Pinterest Settings",
					"type" => "heading",
					"icon" => "home");							
												
$options[] = array( "name" => __("Pinterest Username", "colabsthemes" ),
					"desc" => __("Entry pinterest username in here.", "colabsthemes" ),
					"id" => $shortname."_username_pinterest",
					"class" => "",
					"type" => "text");
					
$options[] = array( "name" => __( "Count Pinterest", "colabsthemes" ),
					"desc" => __( "Entry the limit for pinterest.", "colabsthemes" ),
					"id" => $shortname."_piccount_pinterest",
					"std" => "10",
					"class" => "",
					"type" => "text");
					
$options[] = array( "name" => __("Specific Board (optional):", "colabsthemes" ),
					"desc" => __("Enter the specific board for the pinterest", "colabsthemes" ),
					"id" => $shortname."_board_pinterest",
					"class" => "",
					"type" => "text");		

/* //Social Settings	 */				
$options[] = array( "name" => __("Social Networking","colabsthemes"),
					"icon" => "misc",
					"type" => "heading");
                    
$options[] = array( "name" => __("Facebook","colabsthemes"),
					"desc" => __("Enter your Facebook profile URL","colabsthemes"),
					"id" => $shortname."_social_facebook",
					"std" => "",
					"type" => "text");  

$options[] = array( "name" => __("Twitter","colabsthemes"),
					"desc" => __("Enter your Twitter URL","colabsthemes"),
					"id" => $shortname."_social_twitter",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __( "Delicious URL", "colabsthemes" ),
					"desc" => __( "Enter your Delicious URL", "colabsthemes" ),
					"id" => $shortname."_social_delicious",
					"std" => "",
					"type" => "text");
					
$options[] = array( "name" => __( "Flickr URL", "colabsthemes" ),
					"desc" => __( "Enter your Flickr URL", "colabsthemes" ),
					"id" => $shortname."_social_flickr",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __( "LastFM URL", "colabsthemes" ),
					"desc" => __( "Enter your LastFM account", "colabsthemes" ),
					"id" => $shortname."_social_lastfm",
					"std" => "",
					"type" => "text");					

$options[] = array( "name" => __( "Picasa URL", "colabsthemes" ),
					"desc" => __( "Enter your Picasa account", "colabsthemes" ),
					"id" => $shortname."_social_picasa",
					"std" => "",
					"type" => "text");
					
$options[] = array( "name" => __( "WordPress URL", "colabsthemes" ),
					"desc" => __( "Enter your WordPress account", "colabsthemes" ),
					"id" => $shortname."_social_wordpress",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __( "Yahoo URL", "colabsthemes" ),
					"desc" => __( "Enter your Yahoo account", "colabsthemes" ),
					"id" => $shortname."_social_yahoo",
					"std" => "",
					"type" => "text");
					
$options[] = array( "name" => __( "Google URL", "colabsthemes" ),
					"desc" => __( "Enter your Google URL", "colabsthemes" ),
					"id" => $shortname."_social_google",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __( "Youtube URL", "colabsthemes" ),
					"desc" => __( "Enter your Youtube URL", "colabsthemes" ),
					"id" => $shortname."_social_youtube",
					"std" => "",
					"type" => "text");					

$options[] = array( "name" => __( "LinkedIn URL", "colabsthemes" ),
					"desc" => __( "Enter your LinkedIn URL", "colabsthemes" ),
					"id" => $shortname."_social_linked",
					"std" => "",
					"type" => "text");
					
$options[] = array( "name" => __("Enable/Disable Social Share Button","colabsthemes" ),
					"desc" => __("Select which social share button you would like to enable.","colabsthemes" ),
					"id" => $shortname."_share",
					"std" => array("fblike","twitter","google_plusone"),
					"type" => "multicheck2",
                    "class" => "",
					"options" => array(
                                    "fblike" => "Facebook Like Button",
                                    "twitter" => "Twitter Share Button",
                                    "google_plusone" => "Google +1 Button",
									"pinterest" => "Pinterest",
									"linkedin" => "Linked In"
                                )
                    );
                    
// Open Graph Settings
$options[] = array( "name" => __("Open Graph Settings","colabsthemes"),
					"type" => "heading",
					"icon" => "graph");

$options[] = array( "name" => __("Open Graph","colabsthemes"),
					"desc" => __("Enable or disable Open Graph Meta tags.","colabsthemes"),
					"id" => $shortname."_og_enable",
					"type" => "select2",
                    "std" => "",
                    "class" => "collapsed",
					"options" => array("" => "Enable", "disable" => "Disable") );

$options[] = array( "name" => __("Site Name","colabsthemes"),
					"desc" => __("Open Graph Site Name ( og:site_name ).","colabsthemes"),
					"id" => $shortname."_og_sitename",
					"std" => "",
                    "class" => "hidden",
					"type" => "text");

$options[] = array( "name" => __("Admin","colabsthemes"),
					"desc" => __("Open Graph Admin ( fb:admins ).","colabsthemes"),
					"id" => $shortname."_og_admins",
					"std" => "",
                    "class" => "hidden",
					"type" => "text");

$options[] = array( "name" => __("Image","colabsthemes"),
					"desc" => __("You can put the url for your Open Graph Image ( og:image ).","colabsthemes"),
					"id" => $shortname."_og_img",
					"std" => "",
                    "class" => "hidden last",
					"type" => "text");
 
//Dynamic Images 					                   
$options[] = array( "name" => __("Thumbnail Settings","colabsthemes"),
					"type" => "heading",
					"icon" => "image");
                    
$options[] = array( "name" => __("WordPress Featured Image","colabsthemes"),
					"desc" => __("Use WordPress Featured Image for post thumbnail.","colabsthemes"),
					"id" => $shortname."_post_image_support",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox");

$options[] = array( "name" => __("WordPress Featured Image - Dynamic Resize","colabsthemes"),
					"desc" => __("Resize post thumbnail dynamically using WordPress native functions (requires PHP 5.2+).","colabsthemes"),
					"id" => $shortname."_pis_resize",
					"std" => "true",
					"class" => "hidden",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("WordPress Featured Image - Hard Crop","colabsthemes"),
					"desc" => __("Original image will be cropped to match the target aspect ratio.","colabsthemes"),
					"id" => $shortname."_pis_hard_crop",
					"std" => "true",
					"class" => "hidden last",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("TimThumb Image Resizer","colabsthemes"),
					"desc" => __("Enable timthumb.php script which dynamically resizes images added thorugh post custom field.","colabsthemes"),
					"id" => $shortname."_resize",
					"std" => "true",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("Automatic Thumbnail","colabsthemes"),
					"desc" => __("Generate post thumbnail from the first image uploaded in post (if there is no image specified through post custom field or WordPress Featured Image feature).","colabsthemes"),
					"id" => $shortname."_auto_img",
					"std" => "true",
					"type" => "checkbox");
                    
$options[] = array( "name" => __("Thumbnail Image in RSS Feed","colabsthemes"),
					"desc" => __("Add post thumbnail to RSS feed article.","colabsthemes"),
					"id" => $shortname."_rss_thumb",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __("Thumbnail Image Dimensions","colabsthemes"),
					"desc" => __("Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.","colabsthemes"),
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_thumb_w',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_thumb_h',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Height')
								  ));

$options[] = array( "name" => __("Custom Field Image","colabsthemes"),
					"desc" => __("Enter your custom field image name to change the default name (default name: image).","colabsthemes"),
					"id" => $shortname."_custom_field_image",
					"std" => "",
					"type" => "text");
					
// Analytics ID, RSS feed
$options[] = array( "name" => __("Analytics ID, RSS feed","colabsthemes"),
					"type" => "heading",
					"icon" => "statistics");

$options[] = array( "name" => __("Enable PressTrends Tracking","colabsthemes"),
					"desc" => __("PressTrends is a simple usage tracker that allows us to see how our customers are using our themes, so that we can help improve them for you. <strong>None</strong> of your personal data is sent to PressTrends.","colabsthemes"),
					"id" => $shortname."_pt_enable",
					"std" => "true",
					"type" => "checkbox");
					
$options[] = array( "name" => __("GoSquared Token","colabsthemes"),
					"desc" => __("You can use <a href='http://www.gosquared.com/livestats/?ref=11674'>GoSquared</a> real-time web analytics. Enter your <strong>GoSquared Token</strong> here (ex. GSN-893821-D).","colabsthemes"),
					"id" => $shortname."_gosquared_id",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __("Google Analytics","colabsthemes"),
					"desc" => __("Manage your website statistics with Google Analytics, put your Analytics Code here. ","colabsthemes"),
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");

//Contact Form */
$options[] = array( "name" => __("Contact Form","colabsthemes"),
					"type" => "heading",
					"icon" => "general");
                    
$options[] = array( "name" => __("Destination Email Address","colabsthemes"),
					"desc" => __("All inquiries made by your visitors through the Contact Form page will be sent to this email address.","colabsthemes"),
					"id" => $shortname."_contactform_email",
					"std" => "",
					"type" => "text"); 
					
// Add extra options through function
if ( function_exists("colabs_options_add") )
	$options = colabs_options_add($options);

if ( get_option('colabs_template') != $options) update_option('colabs_template',$options);      
if ( get_option('colabs_themename') != $themename) update_option('colabs_themename',$themename);   
if ( get_option('colabs_shortname') != $shortname) update_option('colabs_shortname',$shortname);
if ( get_option('colabs_manual') != $manualurl) update_option('colabs_manual',$manualurl);

//PressTrends
$colabs_pt_auth = "3bkal5czl80s94uurt8ku5rj3riwbtjt7"; 
update_option('colabs_pt_auth',$colabs_pt_auth);

// CoLabs Metabox Options
// Start name with underscore to hide custom key from the user
$colabs_metaboxes = array();
$colabs_metabox_settings = array();
global $post;

    //Metabox Settings
    $colabs_metabox_settings['post'] = array(
                                'id' => 'colabsthemes-settings',
								'title' => 'ColorLabs' . __( ' Post Detail Settings', 'colabsthemes' ),
								'callback' => 'colabsthemes_metabox_create',
								'page' => 'post',
								'context' => 'normal',
								'priority' => 'high',
                                'callback_args' => ''
								);
    $colabs_metabox_settings['photograph'] = array(
                                'id' => 'colabsthemes-settings',
								'title' => 'ColorLabs' . __( ' Photograph Detail Settings', 'colabsthemes' ),
								'callback' => 'colabsthemes_metabox_create',
								'page' => 'photograph',
								'context' => 'normal',
								'priority' => 'high',
                                'callback_args' => ''
								);
												
                                   							

if ( ( get_post_type() == 'post') ) {
	
	$colabs_metaboxes[] = array (  "name"  => $shortname."_single_top",
					            "std"  => "Image",
					            "label" => "Item to Show",
					            "type" => "radio",
					            "desc" => "Choose Image/Embed Code to appear at the single top.",
								"options" => array(	"none" => "None",
													"single_image" => "Image",
													"single_video" => "Embed" ));
	$colabs_metaboxes[] = array (	"name" => "image",
								"label" => "Post Custom Image",
								"type" => "upload",
                                "class" => "single_image",
								"desc" => "Upload an image or enter an URL.");
	
	$colabs_metaboxes[] = array (  "name"  => $shortname."_embed",
					            "std"  => "",
					            "label" => "Video Embed Code",
					            "type" => "textarea",
                                "class" => "single_video",
					            "desc" => "Enter the video embed code for your video (YouTube, Vimeo or similar)");
							
}
if ( ( get_post_type() == 'photograph') || ( !get_post_type() ) ) {

	$colabs_metaboxes[] = array (  "name"  => $shortname."_feature_photograph",
					            "std"  => "",
					            "label" => __("Feature","colabsthemes"),
					            "type" => "checkbox",
					            "desc" => __("","colabsthemes"));
  
} // End photograph


// Add extra metaboxes through function
if ( function_exists("colabs_metaboxes_add") ){
	$colabs_metaboxes = colabs_metaboxes_add($colabs_metaboxes);
    }
if ( get_option('colabs_custom_template') != $colabs_metaboxes){
    update_option('colabs_custom_template',$colabs_metaboxes);
    }
if ( get_option('colabs_metabox_settings') != $colabs_metabox_settings){
    update_option('colabs_metabox_settings',$colabs_metabox_settings);
    }
     
}
}



?>