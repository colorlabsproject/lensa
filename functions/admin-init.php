<?php
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Theme Version */
/*-----------------------------------------------------------------------------------*/
function colabs_version_init() {

    $colabs_framework_version = '1.8.2';

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
if ( function_exists( 'wp_get_theme' ) ){
	$theme_data = wp_get_theme();
    $theme_version = $theme_data->Version;
	$theme_name = $theme_data->Name;
}
define( 'COLABS_THEME_VER', $theme_version );
define( 'COLABS_THEME_NAME', $theme_name );
$upload_dir = wp_upload_dir();
define('COLABS_CUSTOM', $upload_dir['basedir'] . '/'.strtolower(COLABS_THEME_NAME).'-custom/');
if (!file_exists(COLABS_CUSTOM)){
	wp_mkdir_p( COLABS_CUSTOM );
}
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
/* Move Custom CSS */
/*-----------------------------------------------------------------------------------*/
function colabs_move_old_custom(){
	if ( get_option( 'colabs_custom_old_'.strtolower(COLABS_THEME_NAME) ) != true ) {
		$wp_filesystem = WP_Filesystem();
		global $wp_filesystem;
		$context = get_template_directory().'/custom/';
		$target_dir = $wp_filesystem->find_folder($context);
		$target_file = trailingslashit($target_dir).'custom.css';
		$old_custom = $wp_filesystem->get_contents($target_file);
		if ( $wp_filesystem->put_contents( COLABS_CUSTOM . '/custom.css' , $old_custom, FS_CHMOD_FILE) ){
			update_option( 'colabs_custom_old_'.strtolower(COLABS_THEME_NAME), true );
		}
	}
	if ( get_option( 'colabs_custom_func_old_'.strtolower(COLABS_THEME_NAME) ) != true ) {
		$wp_filesystem = WP_Filesystem();
		global $wp_filesystem;
		$context = get_template_directory().'/custom/';
		$target_dir = $wp_filesystem->find_folder($context);
		$target_file = trailingslashit($target_dir).'custom_functions.php';
		$old_custom = $wp_filesystem->get_contents($target_file);
		if ( $wp_filesystem->put_contents( COLABS_CUSTOM . '/custom_functions.php' , $old_custom, FS_CHMOD_FILE) ){
			update_option( 'colabs_custom_func_old_'.strtolower(COLABS_THEME_NAME), true );
		}
	}

}

add_action( 'admin_head', 'colabs_move_old_custom', 10 );


?>