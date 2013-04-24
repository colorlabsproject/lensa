<?php
add_action('init', 'photograph_register');

if ( ! function_exists( 'photograph_register' ) ) {
	function photograph_register() {
		$args = array(
			'description' => 'Photograph Post Type',
			'show_ui' => true,
			'menu_position' => 4,
			'labels' => array(
				'name'=> __('Photographs',"colabsthemes"),
				'singular_name' => __('Photograph',"colabsthemes"),
				'add_new' => __('Add New',"colabsthemes"),
				'add_new_item' => __('Add New',"colabsthemes"),
				'edit' => __('Edit Photograph',"colabsthemes"),
				'edit_item' => __('Edit Photograph',"colabsthemes"),
				'new-item' => __('New Photograph',"colabsthemes"),
				'view' => __('View Photograph',"colabsthemes"),
				'view_item' => __('View Photograph',"colabsthemes"),
				'search_items' => __('Search Photograph',"colabsthemes"),
				'not_found' => __('No Photograph Found',"colabsthemes"),
				'not_found_in_trash' => __('No Photograph Found in Trash',"colabsthemes"),
				'parent' => __('Parent Photograph',"colabsthemes"),
				'all_items' => __('All Photographs',"colabsthemes")
			),
			'public' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'photograph'),
			'has_archive' => true, 
			'taxonomies' => array( 'photograph-categories' ), 
			'supports' => array('title', 'thumbnail', 'author', 'comments', 'excerpt', 'editor'),
			'menu_icon' => get_template_directory_uri() .'/images/photograph.png'
		);

		register_post_type( 'photograph' , $args );
		
		 
		register_taxonomy('photograph-categories',
				array ( 'photograph' ),
				array (
				'labels' => array (
						'name' => __('Categories',"colabsthemes"),
						'singular_name' => __('Categoriy',"colabsthemes"),
						'search_items' => __('Search Photograph Categories',"colabsthemes"),
						'popular_items' => __('Popular Photograph Categories',"colabsthemes"),
						'all_items' => __('All Photograph Categories',"colabsthemes"),
						'parent_item' => __('Parent Photograph Categories',"colabsthemes"),
						'parent_item_colon' => __('Parent Photograph Categories:',"colabsthemes"),
						'edit_item' => __('Edit Photograph Categories',"colabsthemes"),
						'update_item' => __('Update Photograph Categories',"colabsthemes"),
						'add_new_item' => __('Add New Photograph Categories',"colabsthemes"),
						'new_item_name' => __('New Photograph Categories',"colabsthemes"),
						),
						'hierarchical' =>true,
						'show_ui' => true,
						'show_tagcloud' => true,
						'query_var' => true,
						'rewrite' => array( 'slug' => 'photograph-categories' ),
				));
		flush_rewrite_rules();
		
	}
}

add_filter("manage_edit-photograph_columns", "photograph_edit_columns");   
  
function photograph_edit_columns($columns){  
        $columns = array(  
            "cb" => "<input type=\"checkbox\" />",  
            "title" => __("Photograph","colabsthemes"), 
            "photograph-categories" => __("Categories","colabsthemes"), 
            "likes" => __("Likes","colabsthemes"),
            "feature" => __("Featured","colabsthemes"), 
            "photo" => __("Photo","colabsthemes"),
            "date" => __("Date","colabsthemes"),
              
        );  
  
        return $columns;  
}  

add_action("manage_photograph_posts_custom_column",  "photograph_custom_columns"); 
  
function photograph_custom_columns($column){  
        global $post;  
        switch ($column){    
            case "photograph-categories":  
                echo get_the_term_list($post->ID, 'photograph-categories', '', ', ','');  
                break; 
            case "likes":
				if(function_exists('get_like')) echo get_like($post-ID);
				break;
            case "photo":
				if(has_post_thumbnail()) the_post_thumbnail( array(75,75) );
				break;
            case "feature":
				$url = wp_nonce_url( admin_url('admin-ajax.php?action=photograph-feature&id=' . $post->ID), 'photograph-feature' );
				if(get_post_meta($post->ID, 'colabs_feature_photograph', true)=='true')
					$icon ='<img src="'.get_template_directory_uri().'/images/on.png" />';
				else
					$icon ='<img src="'.get_template_directory_uri().'/images/off.png" />';
				echo '<a href="'.$url.'">'.$icon.'</a>';
				break;	
        }  
}  

function photograph_feature() {

	if ( ! is_admin() ) die;

	if ( ! current_user_can('edit_posts') ) wp_die( __('You do not have sufficient permissions to access this page.', 'colabsthemes') );

	if ( ! check_admin_referer('photograph-feature')) wp_die( __('You have taken too long. Please go back and retry.', 'colabsthemes') );

	$post_id = isset( $_GET['id'] ) && (int) $_GET['id'] ? (int) $_GET['id'] : '';

	if (!$post_id) die;

	$post = get_post($post_id);

	if ( ! $post || $post->post_type !== 'photograph' ) die;

	$featured = get_post_meta( $post->ID, 'colabs_feature_photograph', true );

	if ( $featured == 'true' )
		update_post_meta($post->ID, 'colabs_feature_photograph', 'false');
	else
		update_post_meta($post->ID, 'colabs_feature_photograph', 'true');

	wp_safe_redirect( remove_query_arg( array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer() ) );
}

add_action('wp_ajax_photograph-feature', 'photograph_feature');

// CREATE SORT WITH CUSTOM TAXONOMIES
add_filter( 'manage_edit-photograph_sortable_columns', 'photograph_sortable_columns' );

function photograph_sortable_columns( $columns ) {

	$columns['photograph-categories'] = 'photograph-categories';

	return $columns;
}

add_action( 'restrict_manage_posts','photograph_type_filter_list' );
function photograph_type_filter_list() {
  $screen = get_current_screen();
  global $wp_query;
  if ( $screen->post_type == 'photograph' ) {
    wp_dropdown_categories(array(
						'show_option_all' => 'Show All photograph Category',
						'taxonomy' => 'photograph-categories',
						'name' => 'photograph-categories',
						'orderby' => 'name',
						'selected' =>( isset( $wp_query->query['photograph-categories'] ) ?
						$wp_query->query['photograph-categories'] : '' ),
					  'hierarchical' => false,
					  'depth' => 3,
					  'show_count' => false,
					  'hide_empty' => true,
			));
	}
}
add_filter( 'parse_query','perform_filtering' );

function perform_filtering( $query )
 {
    $qv = &$query->query_vars;
    if (( $qv['photograph-categories'] ) && is_numeric( $qv['photograph-categories'] ) ) {
      $term = get_term_by( 'id', $qv['photograph-categories'], 'photograph-categories' ); 
			$qv['photograph-categories'] = $term->slug;
		}

}
?>