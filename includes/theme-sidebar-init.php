<?php

// Register widgetized areas

if (!function_exists('the_widgets_init')) {
	function the_widgets_init() {
	    if ( !function_exists('register_sidebars') )
	        return;
       
        register_sidebar(array(
            'name' => 'Sidebar',
            'id' => 'colabs_right',
            'description' => __( 'This widget will appear in right sidebar area', 'colabsthemes' ),
            'before_widget' => '<aside id="%1$s" class="%2$s block-background block-inner widget sidebar-right-background">',
            'after_widget' => '</aside>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>'));  
	}
}

add_action( 'init', 'the_widgets_init' );


    
?>