<?php
/**
 * CoLabsThemes Theme Options Customizer
 *
 * Customizer Importer and Settings.
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 * @package CoLabsFramework
 * @subpackage Module
 * 
 *-----------------------------------------------------------------------------------

 TABLE OF CONTENTS

 - var $admin_page
 - var $token
 
 - function CoLabsThemes_Customizer () (Constructor)
 - function init ()
 - function register_admin_screen ()
 - function admin_screen ()
 - function admin_screen_help ()
 - function admin_screen_logic ()
 - function move_admin_menu ()
 - function import ()

 - Create $colabs_customizer Object
-----------------------------------------------------------------------------------*/

class CoLabsThemes_Customizer {
	
	var $admin_page;
	var $token;
	
	function CoLabsThemes_Customizer () {
		$this->admin_page = '';
		$this->token = 'colabsthemes-customizer';
	} // End Constructor
	
	/**
	 * init()
	 *
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	
	function init () {
		if ( is_admin() && ( get_option( 'framework_colabs_customizer_disable' ) != 'true' ) ) {
			// Register the admin screen.
			add_action( 'admin_menu', array( &$this, 'register_admin_screen' ), 20 );
			add_action( 'admin_menu', array( &$this, 'move_admin_menu' ), 99 );
		}
	} // End init()
	
	/**
	 * register_admin_screen()
	 *
	 * Register the admin screen within WordPress.
	 *
	 * @since 1.0.0
	 */
	
	function register_admin_screen () {
			
		$this->admin_page = add_submenu_page('colabsthemes_dummy', __( 'Customizer Importer', 'colabsthemes' ), __( 'Customizer Importer', 'colabsthemes' ), 'manage_options', $this->token, array( &$this, 'admin_screen' ) );
			
		// Admin screen logic.
		add_action( 'load-' . $this->admin_page, array( &$this, 'admin_screen_logic' ) );
		
		// Add contextual help.
		add_action( 'contextual_help', array( &$this, 'admin_screen_help' ), 10, 3 );
				
		add_action( 'admin_notices', array( &$this, 'admin_notices' ), 10 );
        
        // Register admin head on colabs customizer page
        add_action( 'admin_print_styles-'.$this->admin_page, array( &$this, 'register_admin_head' ) );
        
	} // End register_admin_screen()
	
    //Updater Load Scripts
    function register_admin_head(){
        
        echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/functions/admin-style.css" media="screen" />';
        echo '<style type="text/css">'
            .'#panel-content .section .description { float:none; width:35% }'
            .'.updated, .error {display: block;}'
            .'</style>';
        
    }//END function register_admin_head

	/**
	 * admin_screen()
	 *
	 * Load the admin screen.
	 *
	 * @since 1.0.0
	 */
	
	function admin_screen () {
		$themename =  get_option( 'colabs_themename' );
		$export_type = 'all';
		
		if ( isset( $_POST['export-type'] ) ) {
			$export_type = esc_attr( $_POST['export-type'] );
		}
?>
<div class="wrap colabs_notice">
	<h2></h2>

<div id="colabs_options" class="<?php if (is_rtl()) { echo 'rtl'; } ?> colabs_customizer">

	<div class="one_col wrap colabs_container">
    
            <div class="clear"></div>
						<?php colabs_theme_check();?>
            <div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save">Options Updated</div></div>
            <div id="colabs-popup-reset" class="colabs-save-popup"><div class="colabs-save-reset">Options Reset</div></div>
            <div style="width:100%;padding-top:15px;"></div>
            <div class="clear"></div>
        
	<div id="main">
        
	<div id="panel-header">
        <?php colabsthemes_options_page_header('save_button=false'); ?>
	</div><!-- #panel-header -->

    <div id="panel-content">

    <div class="section">
    
    	<h3 class="heading"><?php _e( 'Import Customizer Settings', 'colabsthemes' ); ?></h3>
    	<div class="option">
    	<p><?php printf( __( 'If you have tried <a href="%s" target="_blank">ColorLabs Customizer Demo</a> and export the settings into your computer, then you can import the exported file (.json) containing customizer settings to your site here. ', 'colabsthemes' ), 'http://demo.colorlabsproject.com' ); ?></p>
        <p><?php printf( __( 'Or you can customize your theme by using <a href="%s">Wordpress Customizer</a>.', 'colabsthemes' ), wp_customize_url() ); ?></p>
    	<div class="form-wrap">
    		<form enctype="multipart/form-data" method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->token ); ?>">
    			<?php wp_nonce_field( 'colabsthemes-customizer-import' ); ?>
    			<label for="colabsthemes-import-file"><?php printf( __( 'Upload File: (Maximum Size: %s)', 'colabsthemes' ), ini_get( 'post_max_size' ) ); ?></label>
    			<input type="file" id="colabsthemes-import-file" name="colabsthemes-import-file" size="25" />
    			<input type="hidden" name="colabsthemes-customizer-import" value="1" />
    			<input type="submit" class="button" value="<?php _e( 'Upload File and Import', 'colabsthemes' ); ?>" />
    		</form>
    	</div><!--/.form-wrap-->
    	</div><!-- .option -->

    </div><!-- .section -->

    </div><!-- #panel-content -->

    <div id="panel-footer">
      <ul>
          <li class="docs"><a title="Theme Documentation" href="http://colorlabsproject.com/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" >View Documentation</a></li>
          <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank">Submit a Support Ticket</a></li>
          <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank">Suggest a Feature</a></li>
      </ul>
  	</div><!-- #panel-footer -->
	</div><!-- #main -->

	</div><!-- .colabs_container -->
    
</div><!-- #colabs_options -->

</div><!-- .wrap -->
<?php
	
	} // End admin_screen()
	
	/**
	 * admin_screen_help()
	 *
	 * Add contextual help to the admin screen.
	 *
	 * @since 1.0.0
	 */
	
	function admin_screen_help ( $contextual_help, $screen_id, $screen ) {
	
		
		if ( $this->admin_page == $screen->id ) {
		
		$contextual_help =
		  '<h3>' . __( 'Welcome to the ColorLabs Customizer Import Manager.', 'colabsthemes' ) . '</h3>' .
		  '<p>' . __( 'Here are a few notes on using this screen.', 'colabsthemes' ) . '</p>' .
		  '<p>' . __( 'You can import your customizer settings that you have created on ColorLabs Customizer Demo Site.', 'colabsthemes' ) . '</p>' .
		  
		  '<p>' . __( 'To restore your customizer settings from a backup, browse your computer for the file (under the "Import Customizer Settings" heading) and hit the "Upload File and Import" button. This will restore only the settings that have changed since the backup.', 'colabsthemes' ) . '</p>' .
		  
		  '<p><strong>' . __( 'Please note that only valid backup files (.json) generated through the ColorLabs Customizer Demo should be imported.', 'colabsthemes' ) . '</strong></p>' .

		  '<p><strong>' . __( 'Looking for assistance?', 'colabsthemes' ) . '</strong></p>' .
		  '<p>' . sprintf( __( 'Please post your query on the %sColorLabs Support Forums%s where we will do our best to assist you further.', 'colabsthemes' ), '<a href="http://colorlabsproject.com/support/" target="_blank">', '</a>' ) . '</p>';
		
		} // End IF Statement
		
		return $contextual_help;
	
	} // End admin_screen_help()
	
	/**
	 * admin_notices()
	 *
	 * Display admin notices when performing restore.
	 *
	 * @since 1.0.0
	 */
	
	function admin_notices () {
	
		if ( ! isset( $_GET['page'] ) || ( $_GET['page'] != $this->token ) ) { return; }
	
		if ( isset( $_GET['error'] ) && 'true' == $_GET['error'] ) {
			echo '<div id="message" class="error"><p>' . __( 'There was a problem importing your settings. Please Try again.', 'colabsthemes' ) . '</p></div>';
		} else if ( isset( $_GET['error-export'] ) && 'true' == $_GET['error-export'] ) {  
			echo '<div id="message" class="error"><p>' . __( 'There was a problem exporting your settings. Please Try again.', 'colabsthemes' ) . '</p></div>';
		} else if ( isset( $_GET['invalid'] ) && 'true' == $_GET['invalid'] ) {  
			echo '<div id="message" class="error"><p>' . __( 'The import file you\'ve provided is invalid. Please try again.', 'colabsthemes' ) . '</p></div>';
		} else if ( isset( $_GET['imported'] ) && 'true' == $_GET['imported'] ) {  
			echo '<div id="message" class="updated"><p>' . sprintf( __( 'Settings successfully imported. | Return to %sTheme Options%s', 'colabsthemes' ), '<a href="' . admin_url( 'admin.php?page=colabsthemes' ) . '">', '</a>' ) . '</p></div>';
		} // End IF Statement
		
	} // End admin_notices()
	
	/**
	 * admin_screen_logic()
	 *
	 * The processing code to generate restore from a previous backup.
	 *
	 * @since 1.0.0
	 */
	
	function admin_screen_logic () {
		
		if ( isset( $_POST['colabsthemes-customizer-import'] ) && ( $_POST['colabsthemes-customizer-import'] == true ) ) {
			$this->import();
		}
		
	} // End admin_screen_logic()
	
	/**
	 * move_admin_menu()
	 *
	 * Reposition admin menu.
	 *
	 * @since 1.0.0
	 */
	 
	function move_admin_menu () {
		global $submenu;
	
		if ( ! array_key_exists( 'colabsthemes', $submenu ) ) { return ; }
		
		$items_to_move = array();
		$first_item = array();
		$below_items = array();
		
		foreach ( $submenu['colabsthemes'] as $k => $s ) {
			if ( in_array( $s[2], array( 'colabsthemes-customizer' ) ) ) {
				$items_to_move[] = $s;
				unset( $submenu['colabsthemes'][$k] );
			}
			
			if ( in_array( $s[2], array( 'colabsthemes_themes', 'colabsthemes_timthumb_update' ) ) ) {
				$below_items[] = $s;
				unset( $submenu['colabsthemes'][$k] );
			}
			
			if ( $k == 0 ) { $first_item[] = $s; unset( $submenu['colabsthemes'][$k]); }
		}
		
		sort( $items_to_move );
		
		$remaining_items = $submenu['colabsthemes'];
		
		// Grab the first item and unset it from the main array.
		$submenu['colabsthemes'] = array_merge( $first_item, $remaining_items, $items_to_move, $below_items );
	} // End move_admin_menu()
	
	/**
	 * import()
	 *
	 * Import settings from a backup file.
	 *
	 * @since 1.0.0
	 */
	 
	function import() {
		check_admin_referer( 'colabsthemes-customizer-import' ); // Security check.
		
		if ( ! isset( $_FILES['colabsthemes-import-file'] ) ) { return; } // We can't import the settings without a settings file.
		
		// Extract file contents
		$wp_filesystem = WP_Filesystem($cred);
		global $wp_filesystem;
		$upload = $wp_filesystem->get_contents( $_FILES['colabsthemes-import-file']['tmp_name'] );
		
		// Decode the JSON from the uploaded file
		$options = json_decode( $upload, true );
		
		// Check for errors
		if ( ! $options || $_FILES['colabsthemes-import-file']['error'] ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&error=true' ) );
			exit;
		}
		
		// Make sure this is a valid backup file.
		if ( ! isset( $options['colabsthemes-customizer-validator'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&invalid=true' ) );
			exit;
		} else {
			unset( $options['colabsthemes-customizer-validator'] ); // Now that we've checked it, we don't need the field anymore.
		}
		
		// Make sure the options are saved to the global options collection as well.
		$colabs_options = get_option( 'colabs_options' );

		$has_updated = false; // If this is set to true at any stage, we update the main options collection.
		
		// Cycle through data, import settings
		foreach ( (array)$options as $key => $settings ) {
			
			$settings = maybe_unserialize( $settings ); // Unserialize serialized data before inserting it back into the database.
			
			// We can run checks using get_option(), as the options are all cached. See wp-includes/functions.php for more information.
			if ( get_option( $key ) != $settings ) {
				update_option( $key, $settings );
			}
			
			if ( is_array( $colabs_options ) ) {
				if ( isset( $colabs_options[$key] ) && $colabs_options[$key] != $settings ) {
					$colabs_options[$key] = $settings;
					$has_updated = true;
				}
			}
		}
		
		if ( $has_updated == true ) {
			update_option( 'colabs_options', $colabs_options );
		}
		
		// Redirect, add success flag to the URI
		wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&imported=true' ) );
		exit;
		
	} // End import()
	
} // End Class

/**
 * Create $colabs_customizer Object.
 *
 * @since 1.0.0
 * @uses CoLabsThemes_Customizer
 */

$colabs_customizer = new CoLabsThemes_Customizer();
$colabs_customizer->init();


?>