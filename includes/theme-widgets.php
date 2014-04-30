<?php

/*---------------------------------------------------------------------------------*/
/* Loads all the .php files found in /includes/widgets/ directory */
/*---------------------------------------------------------------------------------*/

include( get_template_directory() . '/includes/widgets/widget-colabs-embed.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-flickr.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-twitter.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-tabs.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-fbfriends.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-socialnetwork.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-list-taxonomy.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-latest.php' );
include( get_template_directory() . '/includes/widgets/widget-colabs-subscribe.php' );

/*---------------------------------------------------------------------------------*/
/* Deregister Default Widgets */
/*---------------------------------------------------------------------------------*/
/*if (!function_exists('colabs_deregister_widgets')) {
	function colabs_deregister_widgets(){
	    unregister_widget('WP_Widget_Search');         
	}
}
add_action('widgets_init', 'colabs_deregister_widgets');  
*/


?>