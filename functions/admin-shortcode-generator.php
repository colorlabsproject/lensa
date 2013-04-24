<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: CoLabsThemes shortcode generator.
Date Created: 2011-05-21.

TABLE OF CONTENTS

- Constructor Function
- function init()
- function filter_mce_buttons()
- function filter_mce_external_plugins()

- Utility Functions
- framework_url()
- ajax_action_check_url()
- ajax_action_generate_nonce()

INSTANTIATE CLASS

-----------------------------------------------------------------------------------*/

class CoLabsThemes_Shortcode_Generator {

/*-----------------------------------------------------------------------------------
  Class Variables
  
  * Setup of variable placeholders, to be populated when the constructor runs.
-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------
  Class Constructor
  
  * Constructor function. Sets up the class and registers variable action hooks.
-----------------------------------------------------------------------------------*/

	function CoLabsThemes_Shortcode_Generator () {
	
		// Register the necessary actions on `admin_init`.
		add_action( 'admin_init', array( &$this, 'init' ) );
		
		// wp_ajax_... is only run for logged users.
		add_action( 'wp_ajax_colabs_check_url_action', array( &$this, 'ajax_action_check_url' ) );
		add_action( 'wp_ajax_colabs_shortcodes_nonce', array( &$this, 'ajax_action_generate_nonce' ) );
	} // End CoLabsThemes_Shortcode_Generator()

/*-----------------------------------------------------------------------------------
  init()
  
  * This guy runs the show. Rocket boosters... engage!
-----------------------------------------------------------------------------------*/

	function init() {
		global $pagenow;
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && 'true' == get_user_option( 'rich_editing') )  {
		  	
		  	// Add the tinyMCE buttons and plugins.
			add_filter( 'mce_buttons', array( &$this, 'filter_mce_buttons' ) );
			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );
			
            $wp_version = get_bloginfo( 'version' );
        
            if (version_compare($wp_version, '3.4.0', '<')) {
    			// Register the colourpicker JavaScript.
    			wp_register_script( 'colabs-colourpicker', $this->framework_url() . 'js/colorpicker.js', array( 'jquery' ), '3.6', true ); // Loaded into the footer.
    			wp_enqueue_script( 'colabs-colourpicker' );
    			
    			// Register the colourpicker CSS.
    			wp_register_style( 'colabs-colourpicker', $this->framework_url() . 'css/colorpicker.css' );
    			wp_enqueue_style( 'colabs-colourpicker' );
			}
            
			// Register the custom CSS styles.
			wp_register_style( 'colabs-shortcode-generator', $this->framework_url() . 'css/shortcode-generator.css' );
			wp_enqueue_style( 'colabs-shortcode-generator' );
			
		} // End IF Statement
	
	} // End init()

/*-----------------------------------------------------------------------------------
  filter_mce_buttons()
  
  * Add our new button to the tinyMCE editor.
-----------------------------------------------------------------------------------*/
	
	function filter_mce_buttons( $buttons ) {
		
		array_push( $buttons, '|', 'colabsthemes_shortcodes_button' );
		
		return $buttons;
		
	} // End filter_mce_buttons()

/*-----------------------------------------------------------------------------------
  filter_mce_external_plugins()
  
  * Add functionality to the tinyMCE editor as an external plugin.
-----------------------------------------------------------------------------------*/
	
	function filter_mce_external_plugins( $plugins ) {
		
        $plugins['CoLabsThemesShortcodes'] = wp_nonce_url( esc_url( $this->framework_url() . 'js/shortcode-generator/editor_plugin.js' ), 'colabsframework-shortcode-generator' );
        
        return $plugins;
        
	} // End filter_mce_external_plugins()
	
/*-----------------------------------------------------------------------------------
  Utility Functions
  
  * Helper functions for this class.
-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------
  framework_url()
  
  * Returns the full URL of the CoLabsFramework, including trailing slash.
-----------------------------------------------------------------------------------*/

function framework_url() {
	return esc_url( trailingslashit( get_template_directory_uri() . '/' . basename( dirname( __FILE__ ) ) ) );

} // End framework_url()

/*-----------------------------------------------------------------------------------
  ajax_action_check_url()
  
  * Checks if a given url (via GET or POST) exists.
  * Returns JSON.
  *
  * NOTE: For users that are not logged in this is not called.
  * The client recieves <code>-1</code> in that case.
-----------------------------------------------------------------------------------*/

function ajax_action_check_url() {

	$hadError = true;

	$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

	if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {
		$url = esc_url( $url );
		$file_headers = @get_headers( $url );
		$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
		$hadError     = false;
	}

	echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';

	die();
	
} // End ajax_action_check_url()

/*-----------------------------------------------------------------------------------
  ajax_action_generate_nonce()
  
  * Generate a nonce.
  *
  * NOTE: For users that are not logged in this is not called.
  * The client recieves <code>-1</code> in that case.
-----------------------------------------------------------------------------------*/

function ajax_action_generate_nonce() {
	echo wp_create_nonce( 'colabsframework-shortcode-generator' );
	die();
} // End ajax_action_generate_nonce()
} // End Class

/*-----------------------------------------------------------------------------------
  INSTANTIATE CLASS
-----------------------------------------------------------------------------------*/

$colabs_shortcode_generator = new CoLabsThemes_Shortcode_Generator();
?>