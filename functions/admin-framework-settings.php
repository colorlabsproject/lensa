<?php
/*-----------------------------------------------------------------------------------*/
/* Framework Settings page - colabsthemes_framework_settings_page */
/*-----------------------------------------------------------------------------------*/

function colabsthemes_framework_settings_page(){

    $themename =  get_option( 'colabs_themename' );
    $manualurl =  get_option( 'colabs_manual' );
	$shortname =  'framework_colabs';

    //Framework Version in Backend Head
    $colabs_framework_version = get_option( 'colabs_framework_version' );


    //GET themes update RSS feed and do magic
	include_once(ABSPATH . WPINC . '/feed.php' );

	$pos = strpos($manualurl, 'documentation' );
	$theme_slug = str_replace( "/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation

    //add filter to make the rss read cache clear every 4 hours
    add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 14400;' ) );

	$framework_options = array();

	$framework_options[] = array( 	"name" => __("Admin Settings","colabsthemes"),
									"icon" => "general",
									"type" => "heading" );

	$framework_options[] = array( 	"name" => __("Super User (username)","colabsthemes"),
									"desc" => __("Enter your <strong>username</strong> to hide the Framework Settings and Update Framework from other users. Can be reset from the ","colabsthemes")."<a href='".home_url()."/wp-admin/options.php'>WP options page</a>". __("under","colabsthemes")." <em>framework_colabs_super_user</em>.",
									"id" => $shortname."_super_user",
									"std" => "",
									"class" => "text",
									"type" => "text" );

	$framework_options[] = array( 	"name" => __("Disable SEO Menu Item","colabsthemes"),
									"desc" => __("Disable the <strong>SEO</strong> menu item in the theme menu.","colabsthemes"),
									"id" => $shortname."_seo_disable",
									"std" => "false",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Disable Layout Menu Item","colabsthemes"),
									"desc" => __("Disable the <strong>Layout</strong> menu item in the theme menu.","colabsthemes"),
									"id" => $shortname."_layout_disable",
									"std" => "false",
									"type" => "checkbox" );
                                    
	$framework_options[] = array( 	"name" => __("Disable Custom File Editor Menu Item","colabsthemes"),
									"desc" => __("Disable the <strong>Custom File Editor</strong> menu item in the theme menu.","colabsthemes"),
									"id" => $shortname."_editor_disable",
									"std" => "false",
									"type" => "checkbox" );
                                    
	$framework_options[] = array( 	"name" => __("Disable Sidebar Manager Menu Item","colabsthemes"),
									"desc" => __("Disable the <strong>Sidebar Manager</strong> menu item in the theme menu.","colabsthemes"),
									"id" => $shortname."_sbm_disable",
									"std" => "",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Disable Import/Export Menu Item","colabsthemes"),
									"desc" => __("Disable the <strong>Import/Export</strong> menu item in the theme menu.","colabsthemes"),
									"id" => $shortname."_backupmenu_disable",
									"std" => "false",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Theme Update Notification","colabsthemes"),
									"desc" => __("This will enable notices on your theme options page that there is an update available for your theme.","colabsthemes"),
									"id" => $shortname."_theme_version_checker",
									"std" => "false",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Theme Settings","colabsthemes"),
									"icon" => "general",
									"type" => "heading" );

	$framework_options[] = array( 	"name" => __("Remove Generator Meta Tags","colabsthemes"),
									"desc" => __("This disables the output of generator meta tags in the HEAD section of your site.","colabsthemes"),
									"id" => $shortname."_disable_generator",
									"std" => "false",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Image Placeholder","colabsthemes"),
									"desc" => __("Set a default image placeholder for your thumbnails. Use this if you want a default image to be shown if you haven't added a custom image to your post.","colabsthemes"),
									"id" => $shortname."_default_image",
									"std" => "",
									"type" => "upload" );

	$framework_options[] = array( 	"name" => __("Disable Shortcodes Stylesheet","colabsthemes"),
									"desc" => __("This disables the output of shortcodes.css in the HEAD section of your site.","colabsthemes"),
									"id" => $shortname."_disable_shortcodes",
									"std" => "false",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Output \"Tracking Code\" Option in Header","colabsthemes"),
									"desc" => __("This will output the <strong>Tracking Code</strong> option in your header instead of the footer of your website.","colabsthemes"),
									"id" => $shortname."_move_tracking_code",
									"std" => "false",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Branding","colabsthemes"),
									"icon" => "misc",
									"type" => "heading" );

	$framework_options[] = array( 	"name" => __("Options panel header","colabsthemes"),
									"desc" => __("Change the header image for the ColorLabs Backend.","colabsthemes"),
									"id" => $shortname."_backend_header_image",
									"std" => "",
									"type" => "upload" );

	$framework_options[] = array( 	"name" => __("Options panel icon","colabsthemes"),
									"desc" => __("Change the icon image for the WordPress backend sidebar.","colabsthemes"),
									"id" => $shortname."_backend_icon",
									"std" => "",
									"type" => "upload" );

	$framework_options[] = array( 	"name" => __("WordPress login logo","colabsthemes"),
									"desc" => __("Change the logo image for the WordPress login page.","colabsthemes"),
									"id" => $shortname."_custom_login_logo",
									"std" => "",
									"type" => "upload" );

	global $wp_version;

	if ( $wp_version >= '3.1' ) {

	$framework_options[] = array( 	"name" => __("Admin Bar","colabsthemes"),
									"icon" => "header",
									"type" => "heading" );

	$framework_options[] = array( 	"name" => __("Disable WordPress Admin Bar","colabsthemes"),
									"desc" => __("Disable the WordPress Admin Bar.","colabsthemes"),
									"id" => $shortname."_admin_bar_disable",
									"std" => "false",
									"type" => "checkbox" );

	$framework_options[] = array( 	"name" => __("Enable the ColorLabs Framework Admin Bar enhancements","colabsthemes"),
									"desc" => __("Enable several ColorLabs Framework-specific enhancements to the WordPress Admin Bar, such as custom navigation items for 'Theme Options'.","colabsthemes"),
									"id" => $shortname."_admin_bar_enhancements",
									"std" => "true",
									"type" => "checkbox" );

	}

    update_option( 'colabs_framework_template', $framework_options );

	?>

    <div class="wrap colabs_container">
        <form action="" enctype="multipart/form-data" id="colabsform" method="post">
        <?php
	    	// Add nonce for added security.
	    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-framework-options-update' ); } // End IF Statement

	    	$colabs_nonce = '';

	    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-framework-options-update' ); } // End IF Statement

	    	if ( '' == $colabs_nonce ) {} else {

	    ?>
	    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
	    <?php

	    	} // End IF Statement
	    ?>
            <div class="themever left">
                <div id="icon-colabs" class="icon32"><br /></div>
                <h2><?php echo $themename; ?> <?php echo COLABS_THEME_VER; ?>&nbsp;<?php _e( 'Framework Settings','colabsthemes' ) //your admin panel title ?></h2>
            </div>
            <div class="logocolabs right">
                <a href="http://colorlabsproject.com" title="Visit Our Website"><img src="<?php echo get_template_directory_uri(); ?>/functions/images/colorlabs.png" /></a>
            </div>
            <div class="clear"></div>
            <div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save"><?php _e("Options Updated","colabsthemes"); ?></div></div>
            <div id="colabs-popup-reset" class="colabs-save-popup"><div class="colabs-save-reset"><?php _e("Options Reset","colabsthemes"); ?></div></div>
            <div style="width:100%;padding-top:15px;">
            <div id="support-links" class="left">
                <ul>
				    <li class="docs"><a title="Theme Documentation" href="<?php echo $manualurl; ?>/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" ><?php _e("View Documentation","colabsthemes"); ?></a></li>
                    <span>&#124;</span>
				    <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank"><?php _e("Submit a Support Ticket","colabsthemes"); ?></a></li>
				    <span>&#124;</span>
                    <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank"><?php _e("Suggest a Feature","colabsthemes"); ?></a></li>
                </ul>
            </div>
            <div class="save_bar_top right">
                <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-top left" alt="Working..." />        
                <input type="submit" value="Save All Changes" class="button submit-button button-primary" />
            </div>
            </div>
            <div class="clear"></div>            
            <?php $return = colabsthemes_machine($framework_options); ?>
            <div id="main">
                <div id="colabs-nav">
                    <ul>
                        <?php echo $return[1]; ?>
                    </ul>
                </div>
                <div id="content">
   				<?php echo $return[0]; ?>
                </div>
                <div class="clear"></div>

            </div>
            <div class="save_bar_down right">
            <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
            <input type="submit" value="Save All Changes" class="button submit-button button-primary" />            
            </form>

            <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>" method="post" style="display:inline" id="colabsform-reset">
            <?php
		    	// Add nonce for added security.
		    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-framework-options-reset' ); } // End IF Statement

		    	$colabs_nonce = '';

		    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-framework-options-reset' ); } // End IF Statement

		    	if ( '' == $colabs_nonce ) {} else {

		    ?>
		    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
		    <?php

		    	} // End IF Statement
		    ?>
            <span class="submit-footer-reset">
            <input type="hidden" name="colabs_save" value="reset" />
            </span>
        	</form>


            </div>

    <div style="clear:both;"></div>
    </div><!--wrap-->

<?php } ?>