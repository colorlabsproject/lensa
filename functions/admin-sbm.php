<?php
/*-----------------------------------------------------------------------------------*/
/* 	CoLabsThemes - Sidebar Settings
	Version - V.1.05
	---
	Installation: Make sure all dynamic_sidebar are converted to colabs_sidebar,
	and all is_active_sidebars are converted to colabs_active_sidebar.
	Usage: A new admin panel is created where you can create, edit and
	delete sidebars for your theme.
	Author: Foxinni (http://foxinni.com)

*/
/*-----------------------------------------------------------------------------------*/

//Delete Options
//delete_option( 'sbm_colabs_sbm_options' );

//Created a function that adds a filter to sidebar delegation
function colabs_sidebar($id = 1){

	$id = apply_filters( 'colabs_inject_sidebar', $id );
	dynamic_sidebar($id);

}

//Created a function that adds a filter to active sidebar delegation
function colabs_active_sidebar($id){

	$id = apply_filters( 'colabs_inject_sidebar', $id );
	if(is_active_sidebar($id))
		return true;

	return false;

}
//Function to return the correct sidebar ID on the correct template
function colabs_sbm_sidebar($current_sidebar_id){

	//Load Settings
	$colabs_sbm_options = get_option( 'sbm_colabs_sbm_options' );

	$_is_replaced = false;

	if(is_int($current_sidebar_id)){ $current_sidebar_id = "sidebar-" . $current_sidebar_id; }

	if(!empty($colabs_sbm_options['sidebars'])){

		/*------------------------------------------------------------*/
		/* Re-order sidebars such that they are replaced appropriately.
		/* Ordering as follows:
		/*
		/* 1. Specific Pages.
		/* 2. Page Templates.
		/* 3. Categories.
		/* 4. Tags.
		/* 5. Custom Taxonomies.
		/* 6. Template Hierarchy.
		/*------------------------------------------------------------*/

		$sidebars_by_type = array();
		$reordered_sidebars = array();
		$custom_taxonomies = array();
		$hierarchy_sidebars = array();
		$priority = array( 'page', 'page_template', 'custom_post_type', 'category', 'post_tag', 'taxonomy', 'post_type_archive', 'hierarchy' );

		// Make sure a conditional is always set for each sidebar. If not, use the "type" instead.
		foreach ( $colabs_sbm_options['sidebars'] as $k => $s ) {
			
			if ( '' == $s['conditionals']['conditional'] ) {
				$colabs_sbm_options['sidebars'][$k]['conditionals']['conditional'] = $s['conditionals']['type'];
			}
		
		}
		
		// Separate the sidebars by "conditional".
		foreach ( $colabs_sbm_options['sidebars'] as $k => $s ) {
			
			$sidebars_by_type[$s['conditionals']['conditional']][$k] = $s;
		}

		if ( ! empty( $sidebars_by_type ) ) {
			foreach( $priority as $key ) {

		        if( array_key_exists( $key, $sidebars_by_type ) ) {
	                foreach ( $sidebars_by_type[$key] as $k => $s ) {

						$reordered_sidebars[$k] = $s;

	                }
	                unset( $sidebars_by_type[$key] );
		        } else {
		        	// Place the taxonomies in their own array (there could be any number of custom ones)
		        	$custom_taxonomies[$k] = $s;
		        }
		    }
		}

		// Add the remaining sidebars at the end.
		if ( count( $sidebars_by_type ) ) {
			foreach ( $sidebars_by_type as $k => $v ) {
				if ( count( $v ) > 0 ) {
					foreach ( $v as $i => $j ) {
						$reordered_sidebars[$i] = $j;
					}
				}
			}
		}

		$colabs_sbm_options['sidebars'] = $reordered_sidebars;

		// Get array of all custom taxonomy keys.
		$wp_custom_taxonomy_args = array( '_builtin' => false );
		$colabs_wp_custom_taxonomies = get_taxonomies( $wp_custom_taxonomy_args, 'objects' );
		$tax_keys = array();
		if ( count( $colabs_wp_custom_taxonomies ) > 0 ) { $tax_keys = array_keys( $colabs_wp_custom_taxonomies ); }

		foreach($colabs_sbm_options['sidebars'] as $sidebar){

			$id = $sidebar['conditionals']['id'];
			$type = $sidebar['conditionals']['conditional'];
			$sidebar_id = $sidebar['conditionals']['sidebar_id'];
			$sidebar_to_replace = $sidebar['conditionals']['sidebar_to_replace'];
			$sidebar_piggy = $sidebar['conditionals']['piggy'];

			if(!empty($sidebar_piggy)) {
				$sidebar_id = $sidebar_piggy;
				$sidebar_to_replace = $colabs_sbm_options['sidebars'][$sidebar_id]['conditionals']['sidebar_to_replace'];
			} // End IF Statement

			//For query posts in the wild
			wp_reset_query();

			/*------------------------------------------------------------*/
			/* Support for custom post types, if using WordPress 3.0+.
			/*------------------------------------------------------------*/

			global $wp_version, $post;

			$_post_types = array();

			if ( $wp_version >= '3.0' ) {

				$_args = array(
							'show_ui' => true,
							'public' => true,
							'publicly_queryable' => true,
							'_builtin' => false
							);

				$_post_types = get_post_types( $_args, 'object' );

				// Set certain post types that aren't allowed to have custom sidebars.

				$_disallowed_types = array( 'slide' );

				// Make the array pluggable.

				$_disallowed_types = apply_filters( 'colabsframework_sbm_disallowed_posttypes', $_disallowed_types );

				if ( count( $_post_types ) ) {

					foreach ( $_post_types as $k => $v ) {

						if ( in_array( $k, $_disallowed_types ) ) {

							unset( $_post_types[$k] );

						} // End IF Statement

					} // End FOREACH Loop

				} // End IF Statement
				
				if ( $wp_version >= '3.1' ) {
					$_args = array(
							'show_ui' => true,
							'public' => true,
							'publicly_queryable' => true,
							'_builtin' => false, 
							'has_archive' => true
							);
	
					$_post_types = get_post_types( $_args, 'object' );
				}
				
				if ( count( $_post_types ) ) {

					foreach ( $_post_types as $k => $v ) {

						if ( in_array( $k, $_disallowed_types ) ) {

							unset( $_post_types[$k] );

						} // End IF Statement

					} // End FOREACH Loop

				} // End IF Statement

			} // End IF Statement

	
					// Find conditionals return required sidebar.
					if( 'page' == $type && ! $_is_replaced ) {

						if( is_page() && ( $id == $post->ID ) && ! is_archive() && ! is_home() )
							if($sidebar_to_replace == $current_sidebar_id) {
								$current_sidebar_id = $sidebar_id;

								// Set this to prevent the system from conflicting with the template hierarchy.
								$_is_replaced = true;
							}

					} // End IF Statement

					if( 'category' == $type ) {

						if( is_category($id) || ( is_single() && in_category( $id ) ) ) {
							if($sidebar_to_replace == $current_sidebar_id) {
								$current_sidebar_id = $sidebar_id;

								// Set this to prevent the system from conflicting with the template hierarchy.
								$_is_replaced = true;
							}
						}

					} // End IF Statement

					if( 'post_tag' == $type ) {
						$tag_data = get_tag($id);
						if(is_tag($tag_data->slug))
							if($sidebar_to_replace == $current_sidebar_id) {
								$current_sidebar_id = $sidebar_id;

								// Set this to prevent the system from conflicting with the template hierarchy.
								$_is_replaced = true;
							}
					} // End IF Statement

					if( 'page_template' == $type ) {
						if(is_page_template($id))
							if($sidebar_to_replace == $current_sidebar_id) {
								$current_sidebar_id = $sidebar_id;

								// Set this to prevent the system from conflicting with the template hierarchy.
								$_is_replaced = true;
							}
					} // End IF Statement

					if( 'hierarchy' == $type && ! $_is_replaced ) {

						if('front_page' == $id)
							if( is_front_page() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('home' == $id)
							if( is_home() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('single' == $id)
							if( is_single() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('page' == $id)
							if( is_page() )
								if ( ! in_array( 'colabs_sbm_page_' . $post->ID . '_' . $sidebar_to_replace, array_keys( $colabs_sbm_options['sidebars'] ) ) )
									if($sidebar_to_replace == $current_sidebar_id)
										$current_sidebar_id = $sidebar_id;

						if('singular' == $id)
							if( is_singular() )
								if($sidebar_to_replace == $current_sidebar_id)
								$current_sidebar_id = $sidebar_id;
						if('date' == $id)
							if( is_date() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('archive' == $id)
							if( is_archive() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('category' == $id)
							if( is_category() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('tag' == $id)
							if( is_tag() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('tax' == $id)
							if( is_tax() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('author' == $id)
							if( is_author() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('search' == $id)
							if( is_search() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('paged' == $id)
							if( is_paged() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('attach' == $id)
							if( is_attachment() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;
						if('404' == $id)
							if( is_404() )
								if($sidebar_to_replace == $current_sidebar_id)
									$current_sidebar_id = $sidebar_id;

					} // End IF Statement

					if ( ( '' == $type || in_array( $type, $tax_keys ) ) ) {
						$type_tax = $sidebar['conditionals']['type'];
						if ( $type_tax != '' ) {

							// Get taxonomy query object
							global $wp_query;

							$taxonomy_archive_query_obj = $wp_query->get_queried_object();

							if ( ( is_tax( $taxonomy_archive_query_obj->name, $taxonomy_archive_query_obj->slug ) ) && ( $id == $taxonomy_archive_query_obj->term_id ) ) { $sentinel = true; } // End IF Statement

							if ( ! $sentinel ) {

								// CUSTOM TAXONOMIES
								$wp_custom_taxonomy_args = array( '_builtin' => false );
								$colabs_wp_custom_taxonomies = array();
								$colabs_wp_custom_taxonomies = get_taxonomies( $wp_custom_taxonomy_args, 'objects' );
								$sentinel = false;
								foreach ( $colabs_wp_custom_taxonomies as $colabs_wp_custom_taxonomy ) {
									// checks for match to taxonomy
									if ( $type_tax == $colabs_wp_custom_taxonomy->name ) {
										$term_list = get_the_terms( 0, $colabs_wp_custom_taxonomy->name  );
										$term_results = '';
										if ( $term_list ) {
											foreach ( $term_list as $term_item ) {
												if ( ( is_tax( $colabs_wp_custom_taxonomy->name, $term_item->slug ) ) && ( $id == $term_item->term_id ) ) { $sentinel = true; } // End IF Statement
											} // End FOREACH Loop
										} // End IF Statement
									} // End IF Statement
								} // End FOREACH Loop

							} // End IF Statement

							if ( $sentinel ) {
								if( $sidebar_to_replace == $current_sidebar_id ) {
									$current_sidebar_id = $sidebar_id;
									$_is_replaced = true;
								} // End IF Statement
							} // End IF Statement
						} // End IF Statement
					} // End IF Statement
					
					if( 'post_type_archive' == $type && is_post_type_archive( $id ) ) {
		
						if( $sidebar_to_replace == $current_sidebar_id && is_post_type_archive( $id ) ) {
							$current_sidebar_id = $sidebar_id;
		
							// Set this to prevent the system from conflicting with the template hierarchy.
							$_is_replaced = true;
						}
		
					} // End IF Statement
		
					if( 'custom_post_type' == $type && get_post_type() == $id && is_single() ) {
		
							if($sidebar_to_replace == $current_sidebar_id) {
								$current_sidebar_id = $sidebar_id;
		
								// Set this to prevent the system from conflicting with the template hierarchy.
								$_is_replaced = true;
							}
		
					} // End IF Statement

			// } // End Custom Post Type IF Statement

		} // End FOREACH Loop
	} // End IF Statement

	return $current_sidebar_id;

} // End colabs_sbm_sidebar()

//Adding the filter that injects the right sidebar ID back into the colabs_sidebar function.
add_filter( 'colabs_inject_sidebar','colabs_sbm_sidebar' );

// Register new widgetized areas via plugin
if (!function_exists( 'colabs_sbm_widgets_init')) {
	function colabs_sbm_widgets_init() {
		if ( !function_exists( 'register_sidebars') )
	        return;

		$colabs_sbm_options = get_option( 'sbm_colabs_sbm_options' );
		if(!empty($colabs_sbm_options['sidebars'])){
			foreach($colabs_sbm_options['sidebars'] as $sidebars){
			 	if(empty($sidebars['conditionals']['piggy']))
	   	 			register_sidebar($sidebars['setup']);
	   		}
    	}
    }
}

add_action( 'init', 'colabs_sbm_widgets_init' );

/* Sidebar Settings - Reset Function
--------------------------------------------------*/

function colabsthemes_sbm_reset () {

	$default_data = array( 'sidebars' => array() );

	update_option( 'sbm_colabs_sbm_options', $default_data );

} // End colabsthemes_sbm_reset()

function colabsthemes_sbm_page(){

	global $wp_registered_sidebars;

	// If the user wants to reset the script, we reset the script.
	if ( isset( $_POST['colabs_save'] ) && 'sbm_reset' == $_POST['colabs_save'] ) {

		colabsthemes_sbm_reset();

	} // End IF Statement

	//Load SBM settings
	$init_array = array( 'sidebars' => array(),'settings' => array( 'infobox' => 'show'));
	add_option( 'sbm_colabs_sbm_options',$init_array);
	$colabs_sbm_options = get_option( 'sbm_colabs_sbm_options' );

	//Error checking
	if(!empty($colabs_sbm_options['sidebars'])){
		foreach($colabs_sbm_options['sidebars'] as $key => $options){
			if(empty($key)){ unset($colabs_sbm_options['sidebars'][$key]); }
		}

	}

	//delete_option( 'sbm_colabs_sbm_options' );
    $themename =  get_option( 'colabs_themename' );
    $manualurl =  get_option( 'colabs_manual' );

    //Framework Version in Backend Head
    $colabs_framework_version = get_option( 'colabs_framework_version' );

	//Outout for original sidebars, and new sidebars
	$init_sidebars = '';
	$init_sidebar = '';
	$new_sidebars = '';
	$counter = 0;
	foreach($wp_registered_sidebars as $sidebar){
	    if(!strstr($sidebar['id'],'colabs_sbm_')){
	    	$counter++;
	    	if($counter == 1) { $init_sidebar = $sidebar['name']; }
	    	$init_sidebars .= '<option value="'.$sidebar['id'].'">'.$sidebar['name'].'</option>';
	    } else {
	    	$new_sidebars .= '<option value="'.$sidebar['id'].'">'.$sidebar['name'].'</option>';
	    }
	};

	//Start script output
	?>
	<script type="text/javascript">
	/* Below is the IE fix for .live( 'submit')  error */
	/**
	 * Patch (plugin) for jQuery bug 6359: "live( 'submit') does nothing in IE if
	 * live( 'click') was called before. same with delegate."
	 *
	 * The workaround is to ensure that live( 'click') calls happen *after*
	 * live( 'submit') calls. Fixing live() fixes delegate(), which calls live().
	 *
	 * This plugin uses setTimeout(..., 0) to effect the workaround. That is, it
	 * defers live( 'click') calls to a future execution context. It should work
	 * around the issue in most cases.
	 *
	 * @author Jonathan Aquino
	 * @see http://dev.jquery.com/ticket/6359
	 * @see TEZLA-538
	 */
	(function($) {
		    var originalLive = jQuery.fn.live;
		    jQuery.fn.live = function(types) {
		        var self = this;
		        var args = arguments;
		        if ('click' == types) {
		            setTimeout(function() {
		                originalLive.apply(self, args);
		            }, 0);
		        } else {
		            originalLive.apply(self, args);
		        }
		    };
	})(jQuery);

	//Accordian for the template selecting
	function initMenus() {

		jQuery( '.colabs-sbm-menu ul ul').hide();

		jQuery( '.colabs-sbm-menu ul ul:first').show();


		jQuery( '.colabs-sbm-menu ul#colabs-sbm-menu_ul li a').click(
			function() {
				var checkElement = jQuery(this).next();
				var parent = this.parentNode.parentNode.id;


				if((checkElement.is( 'ul')) && (!checkElement.is( ':visible'))) {
				jQuery( '#' + parent + ' > li a span').text( '[+]' );
					jQuery( '#' + parent + ' ul:visible').slideUp( 'normal' );
					checkElement.slideDown( 'normal' );
					checkElement.parent().find( 'a span').text( '[-]' );

				}
				return false;
			});
		}

	jQuery(document).ready(function() {
		initMenus();

		function colabs_sbm_title(sidebar,name,type){

			if ( 'custom_post_type' == type ) {

				type = 'Custom Post Type';

			} // End IF Statement

			var message = name+', '+type+' ( '+sidebar+')';
			return message;
		}

		function colabs_sbm_description(sidebar,name,type){
			if('post_tag' == type) type = 'tag template';
			if('page_template' == type) type = 'page template';
			if('hierarchy' == type) type = 'template hierarchy';
			if('custom_post_type' == type) type = 'custom post type';
			if('post_type_archive' == type) type = 'post type archive';

			var message = '<?php _e("This sidebar will replace the","colabsthemes"); ?> '+sidebar+' <?php _e("sidebar on the","colabsthemes"); ?> '+name+' '+type+'.';
			return message;
		}

		jQuery( '.item-edit').click(function(){
			jQuery(this).parent().parent().parent().next( '.menu-item-settings').slideToggle(function(){ colabsAdmin.equalHeight(); });
			return false;
		});

		jQuery( '#colabs-sbm-toggle-info').live( 'click',function(){
			var info = jQuery( '#colabs-sbm-builder-meta' );
			if('none' == info.css( 'display')){
				info.fadeIn();
			} else {
				info.fadeOut();
			}
			return false;
		});
		jQuery( '#colabs-sbm-builder-piggy').val(0);
		jQuery( '#colabs-sbm-tab-new').live( 'click',function(){

			jQuery( '.nav-tabs .nav-tab').removeClass( 'nav-tab-active' );
			jQuery(this).addClass( 'nav-tab-active' );
			jQuery( '#colabs-sbm-builder-part-assign').hide();
			jQuery( '#colabs-sbm-builder-part-create').show();
			jQuery( '#colabs-sbm-builder-piggy').val(0);
			jQuery( '#colabs-sbm-label-sb-name span').text( "Sidebar Name" );
			jQuery( '#colabs-sbm-label-sb-desc').show();
			return false;
		});

		jQuery( '#colabs-sbm-tab-existing').live( 'click',function(){

			jQuery( '.nav-tabs .nav-tab').removeClass( 'nav-tab-active' );
			jQuery(this).addClass( 'nav-tab-active' );
			jQuery( '#colabs-sbm-builder-part-create').hide();
			jQuery( '#colabs-sbm-builder-part-assign').show();
			jQuery( '#colabs-sbm-builder-piggy').val(1);
			jQuery( '#colabs-sbm-label-sb-name span').text( "Sidebar Alias" );
			jQuery( '#colabs-sbm-label-sb-desc').hide();
			return false;
		});

		jQuery( '.colabs-sbm-menu ul#colabs-sbm-menu_ul ul li').click(function(){

			var template_data = jQuery(this).children( 'span').text();
	    	var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
	    	var data = {
	    		type: 'colabs_sbm_get_links',
	    		action: 'colabs_sbm_post_action',
	    		data:template_data
	    	};

	    	jQuery.post(ajax_url, data, function(response) {
				
				jQuery('.colabs-sbm-builder').hide("fast");
				jQuery('.colabs-sbm-builder').slideToggle("normal");
				colabsAdmin.equalHeight();

	    		//GET LINKS
				var response = response.split( '|' );
				var type	= response[0];
				var name	= response[1];
				var slug	= response[2];
				var id		= response[3];
				var other	= response[4];
				var cond	= response[5];

				//When user changes sidebar to replace
				jQuery( '#sidebar_to_replace').live( 'change',function(){
		 			var sidebar = "";
          			jQuery(this).children( "option:selected").each(function(){
                		sidebar = jQuery(this).text();
              		});
          			generatedTitle = colabs_sbm_title(sidebar,name,type);
					generatedMessage = colabs_sbm_description(sidebar,name,type);
					jQuery( '#sidebar-title').val(generatedTitle);
					jQuery( '#sidebar-description').val(generatedMessage);

				});

				var html = '';
				var class_name = '';

				generatedTitle = colabs_sbm_title( '<?php echo $init_sidebar; ?>',name,type);
				generatedMessage = colabs_sbm_description( '<?php echo $init_sidebar; ?>',name,type);

				jQuery( '#colabs-sbm-get-links').show();

				//Add Values to Template Info
				var name_input = jQuery( '#colabs-sbm-get-links-inner #template-info-name' );
				name_input.val(name);
				name_input.prev( 'label').html( '<span><?php _e("Name","colabsthemes"); ?>:</span> '+name);

				var type_input = jQuery( '#colabs-sbm-get-links-inner #template-info-type' );
				type_input.val(type);
				type_input.val(type).prev().html( '<span><?php _e("Type","colabsthemes"); ?>:</span> '+type);

				var slug_input = jQuery( '#colabs-sbm-get-links-inner #template-info-slug' );
				slug_input.val(slug);
				slug_input.prev().html( '<span><?php _e("Slug","colabsthemes"); ?>:</span> '+slug);

				var id_input = jQuery( '#colabs-sbm-get-links-inner #template-info-id' );
				id_input.val(id);
				id_input.prev().html( '<span><?php _e("ID","colabsthemes"); ?>:</span> '+id);

				if(other != ''){
					var other_input = jQuery( '#colabs-sbm-get-links-inner #template-info-other' );
					other_input.val(other);
					other_input.prev().html( '<span><?php _e("URL","colabsthemes"); ?>:</span> <small><a href="'+ other +'">'+ other +'</a></small>' );
				} else {
					var other_input = jQuery( '#colabs-sbm-get-links-inner #template-info-other' );
					other_input.val( 'n/a' );
					other_input.prev().html( '<span><?php _e("URL","colabsthemes"); ?>:</span> n/a' );

				}

				//Add Values to Sidebar Builder
				jQuery( '#colabs-sbm-get-links-inner #sidebar-title').val(generatedTitle);
				jQuery( '#colabs-sbm-get-links-inner #sidebar-description').val(generatedMessage);
				jQuery( '#colabs-sbm-get-links-inner #colabs-sbm-builder-conditional').val(cond);


				html += '<label id="colabs-sbm-label-sb-desc"><span>Sidebar description</span> <textarea id="sidebar-description" name="sidebar-description" style="width:230px">'+generatedMessage+'</textarea></label>';
			 	html += '<input id="colabs-sbm-builder-conditional" type="hidden" name="conditional" value="'+cond+'" />';

	    		var success = jQuery( '#colabs-popup-save' );
	    		var loading = jQuery( '.ajax-loading-img' );
	    		loading.fadeOut();
	    		//jQuery( '#colabs-sbm-tip-1').hide(); //Fade tip out

	    	});
	    	return false;
		});

		//Now to save your new sidebar

		jQuery( "#colabs-sbm-get-links .colabs-sbm-controls input[type=submit]").live( "click",function(){

	    	var sidebarTitle = jQuery( '#sidebar-title').val();
	    	if('' == sidebarTitle){ 
	    		alert( '<?php _e("Please add a Sidebar Name!","colabsthemes"); ?>' );
	    		return false;
	    	}

	    	function newValues() {
	    	  var serializedValues = jQuery( "#colabs-sbm-get-links").serialize();
	    	  return serializedValues;
	    	}
	    	jQuery( ":checkbox, :radio").click(newValues);
	    	jQuery( "select").change(newValues);
	    	jQuery( '.ajax-loading-img').fadeIn();
	    	var serializedReturn = newValues();

	    	var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';

	    	var data = {
	    		type: 'colabs_sbm_add_sidebar',
	    		action: 'colabs_sbm_post_action',
	    		data: serializedReturn
	    	};

	    	jQuery.post(ajax_url, data, function(response) {

	    		//Split response up
				var response = response.split( '|' );

				//Only stage is used in this case
				var type	= response[0];
				var slug	= response[1];
				var name	= response[2];
				var id		= response[3];
				var other	= response[4]; // URL's mostly
				var cond	= response[5];
				var stage	= response[6];
				var sbName	= response[7];
				var sbId	= response[8];
				var piggy	= response[9];

	    		var success = jQuery( '#colabs-popup-save' );
	    		var loading = jQuery( '.ajax-loading-img' );
	    		loading.fadeOut();
	    		if(stage == 2){
	    			location.reload();
	    		}
	    	});
	    	return false;
	    });

   	    //Delete a sidebar
	    jQuery( '#colabs-sbm-sidebars .menu-item .submitdelete').live( 'click',function(){

	    	var id = jQuery(this).parent().parent().parent().parent().attr( 'id' );
	    	var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
	    	var data = {
	    		type: 'colabs_sbm_delete-sidebar',
	    		action: 'colabs_sbm_post_action',
	    		data: id
	    	};
	    	if('' == id){
	    		alert( '<?php _e("And error has occured: No ID found.","colabsthemes"); ?>' ); die();
	    	}

	    	jQuery.post(ajax_url, data, function(response) {
	    		//Split response up
				var response = response.split( '|' );

				//Only stage is used in this case
				var ids		= response[0];
				var pos		= response[1];

				jQuery(ids).fadeOut( 'slow',function(){ jQuery(this).remove();});
				if(jQuery(id).hasClass( 'menu-item-depth-0')){
					jQuery(this).next( '.menu-item-depth-1').fadeOut( 'slow',function(){ jQuery(this).remove();});
				}

				jQuery( '#sidebar_to_piggyback option').each(function(){
					if(jQuery(this).val() == pos){ jQuery(this).remove();}
				});
				if(jQuery( '#sidebar_to_piggyback option').length == 0){
					//alert( 'its done' );
					jQuery( '#colabs-sbm-tab-existing').remove();
					jQuery( '#colabs-sbm-tab-new').click();
				};
	    	});
	    	return false;
	    });

	    //Cancel a sidebar
	    jQuery( '#colabs-sbm-sidebars .menu-item .submitcancel').live( 'click',function(){
	    	jQuery(this).parent().parent().slideUp(function(){ colabsAdmin.equalHeight(); });
	    	return false;
	    })

   	    //Edit a sidebar
	    jQuery( '#colabs-sbm-sidebars .menu-item .submitsave').live( 'click',function(){

	    	var clicked = jQuery(this);
	    	var id = clicked.parent().parent().attr( 'id' );

	    		function newValues() {
	    	  		var serializedValues = clicked.parent().parent().parent().parent().find( 'form').serialize();
	    	  		return serializedValues;
	    		}
	    		jQuery( ":checkbox, :radio").click(newValues);
	    		jQuery( "select").change(newValues);
	    		jQuery( '.ajax-loading-img').fadeIn();
	    		var serializedReturn = newValues();

	    		var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';

	    		var data = {
	    			type: 'colabs_sbm_save-sidebar',
	    			action: 'colabs_sbm_post_action',
	    			data: serializedReturn
	    		};

	    		jQuery.post(ajax_url, data, function(response) {

	    			var response = response.split( '|' );
					var name	= response[0];
					var sidebar	= response[1];

	    			var loading = jQuery( '.ajax-loading-img' );
	    			loading.fadeOut();
	    			clicked.parent().parent().parent().parent().find( '.item-title').text(name);
	    			clicked.parent().parent().parent().parent().find( '.item-type').text(sidebar);
	    			clicked.parent().parent().slideUp();

	    		});

	    	return false;


	    });

	    //Delete a sidebar
	    jQuery( '.sbm-content .btn-close').live( 'click',function(){

	    	var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
	    	var data = {
	    		type: 'colabs_sbm_dismiss_intro',
	    		action: 'colabs_sbm_post_action',
	    		data: ''
	    	};

	    	jQuery.post(ajax_url, data, function(response) {
				jQuery( '.sbm-content .info-box').slideUp( 'slow',function(){ jQuery(this).remove();});
	    	});
	    	return false;
	    });

});
</script>

<div class="wrap colabs_container">

    <div id="colabsform">
    <div id="main" class="sbm-content">
    
        <div id="panel-header">
            <?php colabsthemes_options_page_header('reset_button=false&save_button=false'); ?>
        </div><!-- #panel-header -->
    
    	<div id="sidebar-nav" class="colabs-sbm-menu">

			<ul id="colabs-sbm-menu_ul">
			<?php $pages = get_pages(array( 'sort_order' => 'ASC')); ?>
			<?php if(!empty($pages)){ ?>
			<li><a href="#">Pages <span>[-]</span></a>
				<ul>
					<?php
					foreach ($pages as $page) {
						if(array_key_exists ( 'colabs_sbm_page_'.$page->ID,$colabs_sbm_options['sidebars'])){ continue; }
						echo '<li>' . $page->post_title . '<span>type=page&name='. urlencode(  $page->post_title ) .'&slug='.$page->post_name.'&id='. $page->ID.'&other=null</span></li>';
						} ?>
				</ul>
			</li>
			<?php }

			$page_templates = get_page_templates();
			if(!empty($page_templates)){
			?>
			<li><a href="#">Page Templates <span>[+]</span></a>
				<ul>
				<?php
				foreach($page_templates as $name => $template){
					
					echo '<li>' . $name .'<span>type=page_template&name='. urlencode(  $name ) .'&slug='.$template.'&id=null&other=null</span></li>';
 				}; ?>
				</ul>
			</li>
			<?php
			}
			$taxonomies  = get_taxonomies();
			if(!empty($taxonomies)){
				foreach($taxonomies as $taxonomy){
					if('nav_menu' == $taxonomy OR 'link_category' == $taxonomy){ continue; }

			$terms = get_terms($taxonomy);
			if(!empty($terms)){
			?>
			<li><a href="#"><?php echo ucwords(str_replace( '_',' ',$taxonomy)); ?> <span>[+]</span></a>
				<ul><?php

				foreach($terms as $term){
					echo '<li>' . $term->name . '<span>type='. $taxonomy .'&name='. urlencode( $term->name ) .'&slug='.$term->slug.'&id='.$term->term_id.'&other='.$taxonomy.'</span></li>';
				}?>
				</ul>
			</li>
				<?php
				}
			}
			}
			?>

			<li><a href="#">Template Hierarchy <span>[+]</span></a>
				<ul>
					<?php
					$heirarchy = array(	'Front Page' => 'front_page',
										'Home' => 'home',
										'Posts (single.php)' => 'single',
										'Pages' => 'page',
										'Singular (posts and pages)' => 'singular',
										'All Archives' => 'archive',
										'Category Archive' => 'category',
										'Tag Archive' => 'tag',
										'Taxonomy Archive' => 'tax',
										'Author Archive' => 'author',
										'Date Archive' => 'date',
										'Search Results' => 'search',
										'Paged' => 'paged',
										'Attachment' => 'attach',
										'404' => '404'
										);
					foreach($heirarchy as $name => $item){
 					
						echo '<li>'.$name.'<span>type=hierarchy&name='.$name.'&slug='.$item.'&id=null&other=null&other=null</span></li>';
					}
					?>
				</ul>
			</li>
			<?php
				/*------------------------------------------------------------*/
				/* Support for custom post types, if using WordPress 3.0+.
				/*------------------------------------------------------------*/

				global $wp_version;

				if ( $wp_version >= '3.0' ) {

				$_args = array(
							'show_ui' => true,
							'public' => true,
							'publicly_queryable' => true,
							'_builtin' => false
							);

				$_post_types = get_post_types( $_args, 'object' );

				// Set certain post types that aren't allowed to have custom sidebars.

				$_disallowed_types = array( 'slide' );

				// Make the array pluggable.

				$_disallowed_types = apply_filters( 'colabsframework_sbm_disallowed_posttypes', $_disallowed_types );

				if ( count( $_post_types ) ) {

					foreach ( $_post_types as $k => $v ) {

						if ( in_array( $k, $_disallowed_types ) ) {

							unset( $_post_types[$k] );

						} // End IF Statement

					} // End FOREACH Loop

				} // End IF Statement

				if ( count( $_post_types ) ) {
			?>
				<li>
					<a href="#">Custom Post Type <span>[+]</span></a>
					<?php
						$_html = '';

							$_html .= '<ul>' . "\n";

								foreach ( $_post_types as $k => $v ) {

									$_html .= '<li>' . $v->labels->name . '<span>type=custom_post_type&name=' . urlencode( $v->labels->name ) . '&slug=' . urlencode( $k ) . '&id=' . urlencode( $k ) . '&other=' . urlencode( $k ) . '</span></li>' . "\n";

								} // End FOREACH Loop

							$_html .= '</ul>' . "\n";

							echo $_html;

					?>
				</li>
			<?php

					} // End IF Statement

				} // End IF Statement
			?>
			<?php
				/*------------------------------------------------------------*/
				/* Support for post type archives, if using WordPress 3.1+.
				/*------------------------------------------------------------*/
				
				$_archives = array();
				
				if ( count( $_post_types ) ) {
					foreach ( $_post_types as $k => $p ) {
						if ( $p->has_archive ) {
							$_archives[$k] = $p;
						}
					}
				}

				if ( count( $_archives ) ) {
			?>
				<li>
					<a href="#">Post Type Archives <span>[+]</span></a>
					<?php
						$_html = '';
							$_html .= '<ul>' . "\n";
								foreach ( $_archives as $k => $v ) {
									$_html .= '<li>' . $v->labels->name . '<span>type=post_type_archive&name=' . urlencode( $v->labels->name ) . '&slug=' . urlencode( $k ) . '&id=' . urlencode( $k ) . '&other=' . urlencode( $k ) . '</span></li>' . "\n";
								} // End FOREACH Loop
							$_html .= '</ul>' . "\n";
							echo $_html;
					?>
				</li>
			<?php

					} // End IF Statement
			?>
			</ul><!-- #colabs-sbm-menu_ul -->
    
    	</div><!-- /#sidebar-nav -->
    
    	<div id="panel-content">
    
    			<span class="colabs-sbm-tip" id="colabs-sbm-tip-1"><?php _e("Start by selecting a template from the menu on the left for your new sidebar. The new sidebar will be available on the ","colabsthemes"); ?><a href="<?php echo admin_url( 'widgets.php' ); ?>"><?php _e("Widgets","colabsthemes"); ?></a> <?php _e("page","colabsthemes"); ?>.</span>
    
    		<div class="colabs-sbm-builder" style="display:none">
    
    			<form action="" id="colabs-sbm-get-links">
    				<?php
    			    	// Add nonce for added security.
    			    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-sbm-options-update' ); } // End IF Statement
    			    ?>
    				<div id="colabs-sbm-get-links-inner">
    				<?php //Sidebar Options panel get created here... ?>
    				<div id="colabs-sbm-response-builder">
    				<?php //Template Info ?>
    
    				<div id="colabs-sbm-builder-meta">
    				    <div id="colabs-sbm-builder-meta-top"><?php _e("Template Info","colabsthemes"); ?></div>
    				    <div id="colabs-sbm-builder-meta-bottom">
    				    	<label><span><?php _e("Name","colabsthemes"); ?>:</span></label><input type="hidden" name="name" id="template-info-name" value="">
    				    	<label><span><?php _e("Type","colabsthemes"); ?>:</span></label><input type="hidden" name="type" id="template-info-type" value="">
    				    	<label><span><?php _e("Slug","colabsthemes"); ?>:</span></label><input type="hidden" name="name" id="template-info-slug" value="">
    				    	<label><span><?php _e("ID","colabsthemes"); ?>:</span></label><input type="hidden" name="id" id="template-info-id" value="">
    				    	<label class="last"><span><?php _e("URL","colabsthemes"); ?>:</span> <small><a href=""></a></small></label><input type="hidden" name="other" id="template-info-other" value="">
    				    </div>
    				</div>
    				<div class="nav-tabs-nav">
    					<div class="nav-tabs-wrapper">
    						<div class="nav-tabs">
    							<a id="colabs-sbm-tab-new" href="#" class="nav-tab nav-tab-active"><?php _e("Create a new Sidebar","colabsthemes"); ?></a>
    							<?php if(!empty($colabs_sbm_options['sidebars'])) { ?>
    							<a id="colabs-sbm-tab-existing" class="nav-tab" href="#"><?php _e("Use Existing Sidebar","colabsthemes"); ?></a>
    							<?php } ?>
    							<a id="colabs-sbm-toggle-info" class="fr" href="#"><?php _e("Template Info","colabsthemes"); ?><img src="<?php get_template_directory_uri(); ?>/functions/images/ico-info.png" /></a>
    						</div>
    					</div>
    				</div>
    				<div class="builder-header">
    					<label id="colabs-sbm-label-sb-name"><span><?php _e("Sidebar Name","colabsthemes"); ?></span> <input value="" type="text" name="sidebar-title" id="sidebar-title"/></label>
    				</div>
    
    				<div id="colabs-sbm-builder-body">
    				    <div id="colabs-sbm-builder-part-assign" class="colabs-sbm-builder-part-inner">
    				    	<label><span><?php _e("Sidebar to use","colabsthemes"); ?></span>
    				    	<select name="sidebar_to_piggyback" id="sidebar_to_piggyback">
    				    	<?php echo $new_sidebars; ?>
    				    	</select>
    				    </div>
    				    <div id="colabs-sbm-builder-part-create" class="colabs-sbm-builder-part-inner">
    				    	<label><span><?php _e("Sidebar to replace","colabsthemes"); ?></span>
    				    	<select name="sidebar_to_replace" id="sidebar_to_replace">
    				    	<?php echo $init_sidebars; ?>
    				    	</select></label>
    				    </div>
    
    				    	<label id="colabs-sbm-label-sb-desc"><span><?php _e("Sidebar description","colabsthemes"); ?></span> <textarea id="sidebar-description" name="sidebar-description" style="width:230px"></textarea></label>
    				    	<input id="colabs-sbm-builder-conditional" type="hidden" name="conditional" value="'" />
    				    	<input id="colabs-sbm-builder-stage" type="hidden" name="stage" value="2" />
    				    	<input id="colabs-sbm-builder-piggy" type="hidden" name="piggy" value="0" />
    					</div>
    					<div class="colabs-sbm-controls">
    						<input type="submit" value="Add Sidebar" class="button" />
    					</div>
    				</div>
    			</div>
    		</form>

    		<div class="clear"></div>
    	</div><!-- /.colabs-sbm-builder -->
    
    	<div id="colabs-sbm-sidebars" class="js">
    
    			<h3><?php _e("Custom Sidebars","colabsthemes"); ?> <span><?php _e("Newly created sidebars","colabsthemes"); ?></span></h3>
    			<?php
    			//$colabs_sbm_options = get_option( 'sbm_colabs_sbm_options' );
    			$top_array = array();
    			if(!empty($colabs_sbm_options['sidebars'])){
    			?>
    				<ul class="menu ui-sortable" id="menu-to-edit">
    				<?php
    					foreach($colabs_sbm_options['sidebars'] as $sidebar){
    						$sidebar_name = $sidebar['setup']['name'];
    						$id = $sidebar['conditionals']['id'];
    						$sidebar_id = $sidebar['conditionals']['sidebar_id'];
    						$sidebar_to_replace = $sidebar['conditionals']['sidebar_to_replace'];
    						$sidebar_piggy = $sidebar['conditionals']['piggy'];
    						if(empty($sidebar_piggy)){
    							$top_array[$sidebar_id] = array();
    						}
    						if(!empty($sidebar_piggy)){
    							$top_array[$sidebar_piggy][] = $sidebar_id;
    						}
    					}
    
    					//print_r($top_array);
    					foreach($top_array as $top_id => $top_sidebar){
    
    						$sidebar_id = $top_id;
    						$sidebar_name = $colabs_sbm_options['sidebars'][$sidebar_id]['setup']['name'];
    
    						$sidebar_id = $colabs_sbm_options['sidebars'][$sidebar_id]['conditionals']['sidebar_id'];
    						$sidebar_desc = $colabs_sbm_options['sidebars'][$sidebar_id]['setup']['description'];
    						$sidebar_to_replace = $colabs_sbm_options['sidebars'][$sidebar_id]['conditionals']['sidebar_to_replace'];
    						$sidebar_to_replace_nice = $wp_registered_sidebars[$sidebar_to_replace]['name'];
    						?>
    						<li class="menu-item menu-item-depth-0 menu-item-edit-inactive" id="<?php echo $sidebar_id ?>">
    							<form>
    							<dl class="menu-item-bar">
    								<dt class="menu-item-handle">
    									<span class="item-title"><?php echo $sidebar_name; ?></span>
    									<span class="item-controls">
    										<span class="item-type"><?php echo $sidebar_to_replace_nice; ?></span>
    										<a class="item-edit" title="Edit" href="#"><?php _e("Edit","colabsthemes"); ?></a>
    									</span>
    								</dt>
    							</dl>
    							<div class="menu-item-settings" style="display: none;">
    
    							<p class="description description-thin">
    								<label><?php _e("Sidebar Name","colabsthemes"); ?><br />
    									<input type="text" class="widefat edit-menu-item-title" name="sidebar_name" value="<?php echo $sidebar_name; ?>">
    								</label>
    							</p>
    
    							<p class="description description-thin">
    								<label><?php _e("Sidebar to replace","colabsthemes"); ?><br />
    									<select class="widefat sidebar-to-replace" name="sidebar_to_replace">
    										<?php echo $init_sidebars; ?>
    									</select>
    								</label>
    							</p>
    
    							<p class="field-description description description-wide">
    								<label><?php _e("Description","colabsthemes"); ?><br />
    									<textarea class="widefat" rows="3" cols="20" name="sidebar_description"><?php echo $sidebar_desc; ?></textarea>
    								</label>
    							</p>
    							<input type="hidden" name="sidebar_id" value="<?php echo $sidebar_id ?>" />
    							<div class="menu-item-actions description-wide submitbox">
    								<a class="item-delete submitdelete deletion" onclick="return confirm( 'Are you sure you want to delete this sidebar?' );" href="#"><?php _e("Remove This & All Dependents","colabsthemes"); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" href="#"><?php _e("Cancel","colabsthemes"); ?></a> <span class="meta-sep"> | </span> <a class="item-save submitsave" href="#"><?php _e("Save","colabsthemes"); ?></a>
    							</div>
    						</div>
    						<ul class="menu-item-transport"></ul>
    						<script type="text/javascript">
    							jQuery(document).ready(function(){
    								jQuery( '#<?php echo $sidebar_id ?>').find( '.sidebar-to-replace option').each(function(){
    									if(jQuery(this).val() == '<?php echo $sidebar_to_replace; ?>'){
    										jQuery(this).attr( 'selected','selected' );
    									}
    								})
    							})
    						</script>
    					</form>
    				</li>
    
    					<?php
    					if(!empty($top_sidebar)){
    						foreach($top_sidebar as $piggies){
    							$sidebar_id = $piggies;
    							$sidebar_name = $colabs_sbm_options['sidebars'][$sidebar_id]['setup']['name'];
    							$sidebar_id = $colabs_sbm_options['sidebars'][$sidebar_id]['conditionals']['sidebar_id'];
    
    						 	?>
    	     					<li class="menu-item menu-item-depth-1" id="<?php echo $sidebar_id ?>">
    	     						<form>
    									<dl class="menu-item-bar">
    										<dt class="menu-item-handle">
    											<span class="item-title"><?php echo $sidebar_name; ?></span>
    											<span class="item-controls">
    												<span class="item-type"></span>
    												<a class="item-edit" title="Edit" href="#"><?php _e("Edit","colabsthemes"); ?></a>
    											</span>
    										</dt>
    									</dl>
    
    									<div class="menu-item-settings" style="display: none;">

    										<input type="hidden" name="sidebar_id" value="<?php echo $sidebar_id ?>" />
    										<div class="menu-item-actions description-wide submitbox">
    											<a class="item-delete submitdelete deletion" onclick="return confirm( '<?php _e("Are you sure you want to delete this sidebar","colabsthemes"); ?>?' );" href="#"><?php _e("Delete","colabsthemes"); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" href="#"><?php _e("Cancel","colabsthemes"); ?></a> <span class="meta-sep">
    										</div>
    									</div><!-- .menu-item-settings-->
    									<ul class="menu-item-transport"></ul>
    								</form>
    							</li>
    	     					<?php
    						}
    					}
    				}
    			?>
    		</ul>
    		<?php
    		} else { ?>
    		<h5><em><?php _e("No sidebars added yet.","colabsthemes"); ?></em></h5>
    		<?php
    		}
    		?>
    		</div><!-- /#colabs-sbm-sidebars -->
    
    	</div><!-- /#panel-content -->

        <div id="panel-footer">
            <ul>
                <li class="docs"><a title="Theme Documentation" href="http://colorlabsproject.com/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" ><?php _e("View Documentation","colabsthemes"); ?></a></li>
                <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank"><?php _e("Submit a Support Ticket","colabsthemes"); ?></a></li>
                <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank"><?php _e("Suggest a Feature","colabsthemes"); ?></a></li>
            </ul>
            
            <div class="save-bar save_bar_top right">
                <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-top" alt="Working..." />

            	 <form action="" method="post" style="display:inline" id="colabsform-reset">
                    <span class="submit-footer-reset">
                    <input name="reset" type="submit" value="Reset Sidebar Settings" class="button submit-button reset-button" onclick="return confirm( '<?php _e("Click OK to reset. Any Sidebar Settings settings will be lost!","colabsthemes"); ?>' );" />
                    <input type="hidden" name="colabs_save" value="sbm_reset" />
                    </span>
                </form>
            </div><!-- /.save-bar -->
            
        </div><!-- #panel-footer -->

    </div><!-- /.sbm-content -->
    
    <div class="clear"></div>
    </div><!-- /#colabsform -->
         
<div style="clear:both;"></div>
<pre style="display:none">
<?php // print_r($colabs_sbm_options); ?>
</pre>
</div><!--wrap-->

<?php }
/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action - colabs_ajax_callback */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_ajax_colabs_sbm_post_action', 'colabs_sbm_callback' );

function colabs_sbm_callback() {
	global $wpdb, $wp_registered_sidebars; // this is how you get access to the database

	$save_type = $_POST['type'];

	// Sanitise posted value.
	$save_type = strtolower( trim( strip_tags( $save_type ) ) );

	$colabs_sbm_options = get_option( 'sbm_colabs_sbm_options' );

	if('colabs_sbm_get_links' == $save_type){

		$data = $_POST['data'];

		parse_str($data,$data_array);

		$type = $data_array['type'];
		$slug = $data_array['slug'];
		$name = $data_array['name'];
		$id = $data_array['id'];
		$id = intval($id);
		$other = $data_array['other'];

		$output = '';

		if( 'page'== $type){
			$url = get_page_link( $id );
			$conditional = 'page';
			$type = 'Page';
		}
		elseif('page_template' == $type){
			$url = '';
			$name = $name;
			$id = $slug;
			$conditional = 'page_template';
			$type = 'Page Template';
		}
		elseif('category' == $type){
			$url = get_term_link( $id, 'category' );
			$name = $name;
			$conditional = $other;
			$type = 'Category';
		}
		elseif('post_tag' == $type){
			$url = get_term_link( $slug, $other ); // Use the slug to get the term link, not the name.
			$name = $name;
			$conditional = $other;
			$type = 'Tag';
		}
		elseif ( 'hierarchy' == $type){
			$url = '';
			$name = $name;
			$id = $slug;
			$conditional = 'hierarchy';
			$type = 'Template Hierarchy';
		}
		elseif ( 'custom_post_type' == $type){
			$url = '';
			$name = $name;
			$id = $slug;
			$conditional = 'custom_post_type';
			$type = 'Custom Post Type';
		}
		elseif ( 'post_type_archive' == $type){
			$url = '';
			$name = $name;
			$id = $slug;
			$conditional = 'post_type_archive';
			$type = 'Post Type Archive';
		}

		echo "$type|$name|$slug|$id|$url|$conditional";

	}

	if('colabs_sbm_add_sidebar' == $save_type){

		$data = $_POST['data'];
        
		parse_str($data,$data_array);

		$type = $data_array['type'];
		$slug = $data_array['slug'];
		$name = $data_array['name'];
		$id = $data_array['id'];
		$conditional = $data_array['conditional'];
		$other = $data_array['other'];
		$sidebar_to_replace = $data_array['sidebar_to_replace'];
		$sidebar_title = $data_array['sidebar-title'];
		$sidebar_description = $data_array['sidebar-description'];
		$sidebar_piggyback = $data_array['sidebar_to_piggyback'];
		$stage = $data_array['stage'];
		$piggy = $data_array['piggy'];

		if(empty($colabs_sbm_options)){ $colabs_sbm_options = array(); }

		$new_id = "colabs_sbm_" . $conditional . "_" . str_replace( '.','',$id) . "_" . $sidebar_to_replace;

		if($piggy == true){

			$sidebar_piggyback = $sidebar_piggyback;

		} else {

			$sidebar_piggyback = false;
		}

		// Get the data for the sidebar we're looking to replace.
		// This will be used in the before_title, after_title, etc.

		$index = $sidebar_to_replace;

		if ( is_int($index) ) {
			$index = "sidebar-$index";
		} else {
			$index = sanitize_title($index);
			foreach ( (array) $wp_registered_sidebars as $key => $value ) {
				if ( sanitize_title($value['name']) == $index ) {
					$index = $key;
					break;
				}
			}
		}

		$sidebar_data = $wp_registered_sidebars[$index];

		$colabs_sbm_new_set = array( "setup" 		=> array(	'name' => $sidebar_title,
															'id' => $new_id,
															'description' => $sidebar_description,
															'before_widget' => $sidebar_data['before_widget'],
															'after_widget' => $sidebar_data['after_widget'],
															'before_title' => $sidebar_data['before_title'],
															'after_title' => $sidebar_data['after_title']
													),
								"conditionals"	=> array(	'name' => $sidebar_title,
															'type' => $type,
															'id' => $id,
															'conditional' => $conditional,
															'sidebar_id' => $new_id,
															'other' => $other,
															'sidebar_to_replace' => $sidebar_to_replace,
															'piggy' => $sidebar_piggyback
													)
								);

		$colabs_sbm_options['sidebars'][$new_id] = $colabs_sbm_new_set;

		update_option( 'sbm_colabs_sbm_options',$colabs_sbm_options);

		if(!empty($sidebar_piggyback)){
			$piggy = '1';
		} else { $piggy = '0';}

		echo "$type|$name|$slug|$id|$other|$conditional|$stage|$sidebar_title|$new_id|$piggy";
	}

	if('colabs_sbm_delete-sidebar' == $save_type){
		$id = $_POST['data'];
		$ids = array();
		$colabs_sbm_options_temp = $colabs_sbm_options;
		if(!empty($colabs_sbm_options['sidebars'])){
			$pos = '';
			foreach($colabs_sbm_options['sidebars'] as $top_id => $sidebar){
				$sidebar_piggy = $sidebar['conditionals']['piggy'];

				if($id == $top_id OR $id == $sidebar_piggy){
					unset($colabs_sbm_options_temp['sidebars'][$top_id]);
					$ids[] = $top_id;
				}

				if($id == $top_id){ $pos = $id; }
			}
		}
		update_option( 'sbm_colabs_sbm_options',$colabs_sbm_options_temp);
		if(is_array($ids)){
			$id = implode( ',#',$ids);
		}
		echo "#$id|$pos";
	}

	if('colabs_sbm_save-sidebar' == $save_type){

		$data = $_POST['data'];

		parse_str($data,$data_array);

		$id = $data_array['sidebar_id'];
		$sidebar_to_replace = $data_array['sidebar_to_replace'];
		$name = $data_array['sidebar_name'];
		$desc = $data_array['sidebar_description'];


		$colabs_sbm_options['sidebars'][$id]['conditionals']['sidebar_to_replace'] = $sidebar_to_replace;
		$colabs_sbm_options['sidebars'][$id]['setup']['name'] = $name;
		$colabs_sbm_options['sidebars'][$id]['conditionals']['name'] = $name;
		$colabs_sbm_options['sidebars'][$id]['setup']['description'] = $desc;

		$sidebar_to_replace_nice = $wp_registered_sidebars[$sidebar_to_replace]['name'];
		echo "$name|$sidebar_to_replace_nice";

		update_option( 'sbm_colabs_sbm_options',$colabs_sbm_options);

	}

	if('colabs_sbm_dismiss_intro' == $save_type){

		//$data = $_POST['data'];
		$temp_options = get_option( 'sbm_colabs_sbm_options' );
		$temp_options['settings']['infobox'] = 'hide';

		update_option( 'sbm_colabs_sbm_options',$temp_options);


	}
  die();

}



?>