<?php
// CoLabsThemes Admin Interface Functions
/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Framework panel header - colabsthemes_options_page_header
- Generate NAV Header List - colabs_header_nav

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Framework panel header - colabsthemes_options_page_header */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_options_page_header')) {
function colabsthemes_options_page_header($args) {

	/* SET VARIABLES */
    $themename = get_option( 'colabs_themename' );
 
    
    //Defaults
    $save_button = 'true';
    $reset_button = 'true';
    $update_class = 'update-true';
    
    //Set Var
    $upd = colabsthemes_framework_update_check(); 
    if( $upd['update'] == true ){ $upd_class = $update_class; }else{ $upd_class = ''; };
    
	if ( !is_array($args) ) 
		parse_str( $args, $args );
	
	extract($args);
    
 
    
	/* SET HEADER */
?>
    <div id="panel-logo">
    	<a href="http://colorlabsproject.com" title="Visit Our Website"><img src="<?php echo get_template_directory_uri(); ?>/functions/images/colorlabs-logo.png" /></a>
    	<span class="theme-info"><?php echo $themename; ?> <?php echo COLABS_THEME_VER; ?></span>
    </div><!-- #panel-logo -->
    
    <div id="header-nav">
    	<ul>
            <?php
            // SET UP OPTIONS
            $options = array();
            
            // General Settings
            $options[] = array( 'name' => __('Theme Options', 'colabsthemes' ),
            					'desc' => '',
                                'page' => 'colabsthemes',
            					'icon' => 'options',
                                'class' => '',
                                );
    
            $layoutfile = get_template_directory() . '/includes/theme-options-layout.php'; 
            if ( get_option( 'framework_colabs_seo_disable') != 'true' ) {
    
            $options[] = array( 'name' => __('SEO Settings', 'colabsthemes' ),
            					'desc' => '',
                                'page' => 'colabsthemes_seo',
            					'icon' => 'dashboard',
                                'class' => '',
                                );
            } 
    
            if ( get_option( 'framework_colabs_layout_disable') != 'true' && file_exists($layoutfile) ) {
    
            $options[] = array( 'name' => __('Layout Settings', 'colabsthemes' ),
            					'desc' => '',
                                'page' => 'colabsthemes_layout_settings',
            					'icon' => 'options',
                                'class' => '',
                                );
            }
            
            //--Get admin submenu from add_submenu_page() function
            global $submenu;
            $newarr = array();
            $icon = 'options';
            
            // Store current $options['page'] into new array
            foreach ( $options as $key => $val ) { $newarr[] = $val['page']; }
            
            foreach ( $submenu['colabsthemes_dummy'] as $colabsthemes_dummy_submenu ) {
                
                //if page already exists skip it
                if(in_array($colabsthemes_dummy_submenu[2], $newarr)){ continue; }
                
                    //Icon
                    $filename = get_template_directory() .'/functions/images/icon/'. $colabsthemes_dummy_submenu[2] .'.png';
                    if (file_exists($filename)) { $icon = $colabsthemes_dummy_submenu[2]; }

                    $options[] = array( 'name' => $colabsthemes_dummy_submenu[0],
                    					'desc' => '',
                                        'page' => $colabsthemes_dummy_submenu[2],
                    					'icon' => $icon,
                                        'class' => '',
                                        );
	           }
            //-- END Get admin submenu
            
            $options[] = array( 'name' => __('Updates', 'colabsthemes' ),
            					'desc' => '',
                                'page' => 'colabsthemes_framework_update',
            					'icon' => 'update',
                                'class' => $upd_class,
                                );

            //generate nav lists
            echo colabs_header_nav($options);
            ?>
            
    	</ul>
    </div><!-- #header-nav -->
    
    <div class="save-bar save_bar_top right">
        <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-top" alt="Working..." />
        
    	<?php if('true' == $save_button){ ?>
        <input type="submit" value="Save Changes" class="button submit-button button-primary" />
    	<span>&nbsp;</span>
        <?php } ?>
        
    </div>
<?php
    }
}

/*-----------------------------------------------------------------------------------*/
/* Generate NAV Header List - colabs_header_nav */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_header_nav')) {
function colabs_header_nav( $options ) {

    $counter = 0;
	$menu = '';
	$output = '';
    $pagenow = $_GET['page'];
    
	foreach ($options as $value) {
        
        //reset classes
        $classes = array();
        
        //Classes
        if ( $value['page'] == $pagenow ) $classes[] = 'current-page ';
        
        if ( ! empty( $value['class'] ) ) {
            if ( !is_array( $value['class'] ) )
                $classes2 = preg_split( '#\s+#', $value['class'] );
            $classes = array_merge( $classes, $classes2 );
        }
        
        // Separates classes with a single space, collates classes for body element
        $class = 'class="' . implode( ' ', $classes ) . '"';
        
        $output .= '<li '. $class .'>';
        $output .= '<a href="'. admin_url('admin.php?page='. $value['page']) .'" title="'. $value['desc'] .'">';
        $output .= '<img src="'. get_template_directory_uri() .'/functions/images/icon/'. $value['icon'] .'.png"/>';
        $output .= '<span>'. $value['name'] .'</span>';
        $output .= '</a></li>';
        
    }//end foreach
    
    return $output;
}}
?>