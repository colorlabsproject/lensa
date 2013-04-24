<?php
/*-----------------------------------------------------------------------------------*/
/* SEO - colabsthemes_seo_page */
/*-----------------------------------------------------------------------------------*/

function colabsthemes_seo_page(){

    $themename =  get_option( 'colabs_themename' );
    $manualurl =  get_option( 'colabs_manual' );
	$shortname =  'seo_colabs';

    //Framework Version in Backend Head
    $colabs_framework_version = get_option( 'colabs_framework_version' );

    //GET themes update RSS feed and do magic
	include_once(ABSPATH . WPINC . '/feed.php' );

	$pos = strpos($manualurl, 'documentation' );
	$theme_slug = str_replace( "/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation

    //add filter to make the rss read cache clear every 4 hours
    add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 14400;' ) );

	$inner_pages = array(	'b' => 'Page title;',
                            'a' => 'Page title; Blog title',
							'd' => 'Page title; Blog description',
                            'f' => 'Page title; Blog title; Blog description',
							'c' => 'Blog title; Page title;',
							'e' => 'Blog title; Page title; Blog description'
						);

	$seo_options = array();

	$seo_options[] = array( "name" => __("Page Title","colabsthemes"),
					"icon" => "misc",
					"type" => "heading" );

	$seo_options[] = array( "name" => __("Blog Title","colabsthemes"),
					"desc" => __("NOTE: This value corresponds to that in the Settings > General tab in the WordPress Dashboard.","colabsthemes"),
					"id" => "blogname",
					"std" => "",
					"type" => "text" );

	$seo_options[] = array( "name" => __("Blog Description","colabsthemes"),
					"desc" => __("NOTE: This value corresponds to that in the Settings > General tab in the WordPress Dashboard.","colabsthemes"),
					"id" => "blogdescription",
					"std" => "",
					"type" => "text" );

	$seo_options[] = array( "name" => __("Separator","colabsthemes"),
					"desc" => __("Set a character that separates elements of your page titles ( eg. |, -, or &raquo; ).","colabsthemes"),
					"id" => $shortname."_separator",
					"std" => "|",
					"type" => "text" );

	$seo_options[] = array( "name" => __("Custom Page Titles","colabsthemes"),
					"desc" => __("Check this box to gain control over the elements of the page titles (highly recommended).","colabsthemes"),
					"id" => $shortname."_wp_title",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => __("Homepage Title Layout","colabsthemes"),
					"desc" => __("Define the order of the title elements.","colabsthemes"),
					"id" => $shortname."_home_layout",
					"std" => "a",
					"class" => "hidden",
					"options" => array(	'b' => 'Blog title',
                                        'a' => 'Blog title; Blog description',
										'c' => 'Blog description',
                                        'd' => 'Blog description; Blog title'
                                        ),
					"type" => "select2" );

	$seo_options[] = array( "name" => __("Single Title Layout","colabsthemes"),
					"desc" => __("Define the order of the title elements.","colabsthemes"),
					"id" => $shortname."_single_layout",
					"std" => "f",
					"class" => "hidden",
					"options" => $inner_pages,
					"type" => "select2" );

	$seo_options[] = array( "name" => __("Page Title Layout","colabsthemes"),
					"desc" => __("Define the order of the title elements.","colabsthemes"),
					"id" => $shortname."_page_layout",
					"std" => "f",
					"class" => "hidden",
					"options" => $inner_pages,
					"type" => "select2" );

	$seo_options[] = array( "name" => __("Archive Title Layout","colabsthemes"),
					"desc" => __("Define the order of the title elements.","colabsthemes"),
					"id" => $shortname."_archive_layout",
					"std" => "f",
					"class" => "hidden",
					"options" => $inner_pages,
					"type" => "select2" );

	$seo_options[] = array( "name" => __("Page Number","colabsthemes"),
					"desc" => __("Define a text string that precedes page number in page titles.","colabsthemes"),
					"id" => $shortname."_paged_var",
					"std" => "Page",
					"class" => "hidden",
					"type" => "text" );

	$seo_options[] = array( "name" => __("Page Number Position","colabsthemes"),
					"desc" => __("Define the position of page number in page titles.","colabsthemes"),
					"id" => $shortname."_paged_var_pos",
					"std" => "before",
					"class" => "hidden",
					"options" => array(	'before' => 'Before Title',
										'after' => 'After Title'),
					"type" => "select2" );

	$seo_options[] = array( "name" => __("Disable Custom Titles","colabsthemes"),
					"desc" => __("If you prefer to have uniform titles across you theme. Alternatively they will be generated from custom fields and/or plugin data.","colabsthemes"),
					"id" => $shortname."_wp_custom_field_title",
					"std" => "false",
					"class" => "hidden hide",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => __("Description Meta","colabsthemes"),
					"icon" => "misc",
					"type" => "heading" );

	$seo_options[] = array( "name" => __("Homepage Description","colabsthemes"),
					"desc" => __("Choose where to populate the homepage meta description from.","colabsthemes"),
					"id" => $shortname."_meta_home_desc",
					"std" => "b",
                    "class" => "collapsed",
					"options" => array(	"a" => "Off",
										"b" => "From Site Description",
										"c" => "From Custom Homepage Description"),
					"type" => "radio" );

	$seo_options[] = array( "name" => __("Custom Homepage Description","colabsthemes"),
					"desc" => __("Add a custom meta description to your homepage.","colabsthemes"),
					"id" => $shortname."_meta_home_desc_custom",
					"std" => "",
                    "class" => "hidden last",
					"type" => "textarea" );

	$seo_options[] = array( "name" => __("Single Page/Post Description","colabsthemes"),
					"desc" => __("Choose where to populate the single Page/Post meta description from.","colabsthemes"),
					"id" => $shortname."_meta_single_desc",
					"std" => "c",
					"options" => array(	"a" => "Off *",
										"b" => "From Custom Field and/or Plugin Data",
										"c" => "Automatically from Post/Page Content",
										),
					"type" => "radio" );

	$seo_options[] = array( "name" => __("Global Post/Page Description","colabsthemes"),
					"desc" => __("Add a custom meta description to your posts and pages. This will only show if no other data is available from the selection above. This will still be added even if setting above is set to \"Off\".","colabsthemes"),
					"id" => $shortname."_meta_single_desc_sitewide",
					"std" => "",
					"class" => "collapsed",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => __("Global Post/Page Description","colabsthemes"),
					"desc" => __("Add a global post/page description.","colabsthemes"),
					"id" => $shortname."_meta_single_desc_custom",
					"std" => "",
					"class" => "hidden",
					"type" => "textarea" );

	$seo_options[] = array( "name" => __("Keyword Meta","colabsthemes"),
					"icon" => "misc",
					"type" => "heading" );

	$seo_options[] = array( "name" => __("Homepage Keywords","colabsthemes"),
					"desc" => __("Choose where to populate the homepage meta keywords from.","colabsthemes"),
					"id" => $shortname."_meta_home_key",
					"std" => "a",
                    "class" => "collapsed",
					"options" => array(	"a" => "Off",
										"c" => "From Custom Homepage Keywords"),
					"type" => "radio" );

	$seo_options[] = array( "name" => __("Custom Homepage Keywords","colabsthemes"),
					"desc" => __("Add a comma-separated list of keywords to your homepage.","colabsthemes"),
					"id" => $shortname."_meta_home_key_custom",
					"std" => "",
                    "class" => "hidden last",
					"type" => "textarea" );

	$seo_options[] = array( "name" => __("Single Page/Post Keywords","colabsthemes"),
					"desc" => __("Choose where to populate the single page/post meta keywords from.","colabsthemes"),
					"id" => $shortname."_meta_single_key",
					"std" => "c",
					"options" => array(	"a" => "Off *",
										"b" => "From Custom Fields and/or Plugins",
										"c" => "Automatically from Post Tags &amp; Categories"),
					"type" => "radio" );

	$seo_options[] = array( "name" => __("Global Post/Page Keywords","colabsthemes"),
					"desc" => __("Add custom meta keywords to your posts and pages. These will only show if no other data is available from the selection above. These will still be added even if setting above is set to \"Off\".","colabsthemes"),
					"id" => $shortname."_meta_single_key_sitewide",
					"std" => "",
					"class" => "collapsed",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => __("Global Post/Page Keywords","colabsthemes"),
					"desc" => __("Add a comma-separated list of keywords to your posts and pages.","colabsthemes"),
					"id" => $shortname."_meta_single_key_custom",
					"std" => "",
					"class" => "hidden",
					"type" => "textarea" );

	$seo_options[] = array( "name" => __("Indexing Options","colabsthemes"),
					"icon" => "misc",
					"type" => "heading" );

	$seo_options[] = array( "name" => __("Archive Pages to Index","colabsthemes"),
					"desc" => __("Select which archive pages to be indexed. Indexing archive pages may result in duplicate entries in search engines and cause content dilution.","colabsthemes"),
					"id" => $shortname."_meta_indexing",
					"std" => "category",
					"type" => "multicheck",
					"options" => array(	'category' => 'Category Archives',
										'tag' => 'Tag Archives',
										'author' => 'Author Pages',
										'search' => 'Search Results',
										'date' => 'Date Archives'));

	$seo_options[] = array( "name" => __("Add 'follow' Meta to Posts and Pages","colabsthemes"),
					"desc" => __("Check this box to add 'follow' meta to all posts and pages. This means that all links on these pages will be crawled by search engines, including those leading away from your site.","colabsthemes"),
					"id" => $shortname."_meta_single_follow",
					"std" => "",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => __("Advanced Settings","colabsthemes"),
					"icon" => "general",
					"type" => "heading" );

	$seo_options[] = array( "name" => __("Please Read","colabsthemes"),
					"type" => "info",
					"std" => "Data from 3rd party plugin such as All-in-One SEO Pack, Headspace 2 and WordPress SEO By Yoast can also be used where applicable. Use the checkbox below to use 3rd party plugin data.</span>" );

	$seo_options[] = array( "name" => __("Use 3rd Party Plugin Data","colabsthemes"),
					"desc" => __("Meta data added to <strong>custom fields in posts and pages</strong> will be extracted and used where applicable. This typically does not include home page and archive pages and only single post/pages.","colabsthemes"),
					"id" => $shortname."_use_third_party_data",
					"std" => "false",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => __("Hide ColorLabs SEO Settings","colabsthemes"),
					"desc" => __("Check this box to hide the ColorLabs SEO Settings box in the post and page editing screens.","colabsthemes"),
					"id" => $shortname."_hide_fields",
					"std" => "false",
					"type" => "checkbox" );


	update_option( 'colabs_seo_template',$seo_options);


	?>
    <?php

    	if(
    		class_exists( 'All_in_One_SEO_Pack') ||
    		class_exists( 'Headspace_Plugin') ||
    		class_exists( 'WPSEO_Admin' ) ||
    		class_exists( 'WPSEO_Frontend' )
    	  ) {

			echo "<div id='' class='update-nag'><strong>3rd Party SEO Plugin(s) Detected</strong> - Some ".$themename." SEO functionality has been disabled.</div>";
		}

    ?>
    <?php

    	if ( get_option( 'blog_public') == 0 ) {

			echo "<div id='' class='update-nag'><strong>This site is set to Private</strong> - SEO is disabled, change settings <a href='". admin_url( 'options-privacy.php' ) . "'>here</a>.</div>";

		}

    ?>
    <div class="wrap colabs_container">

        <form action="" enctype="multipart/form-data" id="colabsform">
        <?php
	    	// Add nonce for added security.
	    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-seo-options-update' ); } // End IF Statement

	    	$colabs_nonce = '';

	    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-seo-options-update' ); } // End IF Statement

	    	if ( '' == $colabs_nonce ) {} else {

	    ?>
	    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
	    <?php

	    	} // End IF Statement
	    ?>        
        <div class="clear"></div>
				<?php colabs_theme_check();?>
				<div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save"><?php _e("Options Updated","colabsthemes"); ?></div></div>
				<div id="colabs-popup-reset" class="colabs-save-popup"><div class="colabs-save-reset"><?php _e("Options Reset","colabsthemes"); ?></div></div>
				<div style="width:100%;padding-top:15px;"></div>

        <div class="clear"></div>
            <?php $return = colabsthemes_machine($seo_options); ?>
            <div id="main" class="menu-item-settings metabox-holder">
            	<div id="panel-header">
                    <?php colabsthemes_options_page_header('reset_button=false'); ?>
                </div><!-- #panel-header -->

                <div id="sidebar-nav">
                    <ul>
                        <?php echo $return[1]; ?>
                    </ul>
                </div>

                <div id="panel-content">
                	<div class="group help-block"> <p><?php _e("Drag icon on the left and Drop it here to customize","colabsthemes"); ?></p> </div>
                	<?php echo $return[0]; ?>
                	<div class="clear"></div>
                </div>

                <div id="panel-footer">
                    <ul>
                        <li class="docs"><a title="Theme Documentation" href="http://colorlabsproject.com/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" ><?php _e("View Documentation","colabsthemes"); ?></a></li>
                        <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank"><?php _e("Submit a Support Ticket","colabsthemes"); ?></a></li>
                        <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank"><?php _e("Suggest a Feature","colabsthemes"); ?></a></li>
                    </ul>
                    
                    <div class="save-bar save_bar_top right">
                        <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-top" alt="Working..." />
                        <input type="submit" value="Save Changes" class="button submit-button button-primary" />

                    	</form>
                        <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>" method="post" style="display:inline" id="colabsform-reset">
			            <?php
					    	// Add nonce for added security.
					    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-seo-options-reset' ); } // End IF Statement

					    	$colabs_nonce = '';

					    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-seo-options-reset' ); } // End IF Statement

					    	if ( '' == $colabs_nonce ) {} else {

					    ?>
					    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
					    <?php

					    	} // End IF Statement
					    ?>
					    	<input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button button-highlighted" onclick="return confirm( '<?php _e("Click OK to reset all options. All settings will be lost","colabsthemes"); ?>!' );" />
                        	<input type="hidden" name="colabs_save" value="reset" /> 
			        	</form>
                    </div>
                </div><!-- #panel-footer -->

            </div><!-- #main -->
            <div class="clear"></div>            
    <div style="clear:both;"></div>
    </div><!--wrap-->

<?php } ?>