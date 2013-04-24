<?php
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Theme Version */
/*-----------------------------------------------------------------------------------*/
function colabs_version_init() {

    $colabs_framework_version = '1.7.6';

    if ( get_option( 'colabs_framework_version' ) != $colabs_framework_version ) {
    	update_option( 'colabs_framework_version', $colabs_framework_version );
    }

}

add_action( 'init', 'colabs_version_init', 10 );

function colabs_version(){

    $colabs_framework_version = get_option( 'colabs_framework_version' );

	echo "\n<!-- Theme version -->\n";
    echo '<meta name="generator" content="'. esc_attr( get_option( 'colabs_themename' ) ) . ' ' . COLABS_THEME_VER . '" />' ."\n";
    echo '<meta name="generator" content="CoLabsFramework '. esc_attr( $colabs_framework_version ) .'" />' ."\n";

}

// Add or remove Generator meta tags
if ( 'true' == get_option( 'framework_colabs_disable_generator' ) ) {
	remove_action( 'wp_head',  'wp_generator' );
} else {
	add_action( 'wp_head', 'colabs_version', 10 );
}

// Define directory constants
define('COLABS_FRAMEWORK', get_template_directory_uri() . '/functions');
define('COLABS_FRAMEWORK_CSS', COLABS_FRAMEWORK . '/css');
define('COLABS_FRAMEWORK_IMAGES', COLABS_FRAMEWORK . '/images');
define('COLABS_FRAMEWORK_JS', COLABS_FRAMEWORK . '/js');
define('COLABS_FUNCTIONS', get_template_directory_uri() . '/includes');
define('COLABS_FUNCTIONS_CSS', COLABS_FUNCTIONS . '/css');
define('COLABS_FUNCTIONS_JS', COLABS_FUNCTIONS . '/js');
define('COLABS_FUNCTIONS_WIDGETS', COLABS_FUNCTIONS . '/widgets');
define('COLABS_CUSTOM', get_stylesheet_directory() . '/custom');
if ( function_exists( 'wp_get_theme' ) ){
	$theme_data = wp_get_theme();
    $theme_version = $theme_data->Version;
	$theme_name = $theme_data->Name;
}
define( 'COLABS_THEME_VER', $theme_version );
define( 'COLABS_THEME_NAME', $theme_name );

/*-----------------------------------------------------------------------------------*/
/* Load the required Admin Panel Files */
/*-----------------------------------------------------------------------------------*/
$functions_path = get_template_directory() . '/functions/';
require_once ($functions_path . 'admin-functions.php');				// Custom functions and plugins
require_once ($functions_path . 'admin-setup.php');					// Options panel variables and functions
require_once ($functions_path . 'admin-custom.php');                // Custom fields 
require_once ($functions_path . 'admin-interface.php');				// Admin Interfaces (options,framework, seo)
require_once ($functions_path . 'admin-interface-functions.php');	// Admin Interface - Functions
require_once ($functions_path . 'admin-framework-settings.php' );	// Framework Settings
require_once ($functions_path . 'admin-dashboard.php' );            // Dashboard Settings
require_once ($functions_path . 'admin-layout.php' );               // Layout Settings
require_once ($functions_path . 'admin-update.php' );               // Framework Updater Settings
require_once ($functions_path . 'admin-editor.php' );               // Custom File Editor
require_once ($functions_path . 'admin-seo.php');					// Admin Panel SEO controls
require_once ($functions_path . 'admin-medialibrary-uploader.php'); // Admin Panel Media Library Uploader Functions // 2011-28-05.
require_once ($functions_path . 'admin-readme.php');				// Admin Panel Readme Function
require_once ($functions_path . 'admin-hooks.php');					// Definition of CoLabsHooks
require_once ($functions_path . 'admin-sbm.php' ); 					// Framework Sidebar Manager
require_once ($functions_path . 'admin-shortcode-generator.php'); 	// Admin Panel Shortcode generator // 2011-05-27.
require_once ($functions_path . 'admin-shortcodes.php');			// CoLabs Shortcodes
require_once ($functions_path . 'admin-backup.php' ); 				// Theme Options Backup // 2011-08-26.
require_once ($functions_path . 'admin-customizer.php' ); 			// Theme Customizer Manager // 2012-07-20.
require_once ($functions_path . 'admin-theme-editor.php' );			// Add syntax highlighting for theme editor
/*-----------------------------------------------------------------------------------*/
/* Load the required action */
/*-----------------------------------------------------------------------------------*/

?>