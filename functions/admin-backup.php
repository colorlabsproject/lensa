<?php
/**
 * CoLabsThemes Theme Options Backup
 *
 * Backup your "Theme Options" to a downloadable text file.
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
 
 - function CoLabsThemes_Backup () (Constructor)
 - function init ()
 - function register_admin_screen ()
 - function admin_screen ()
 - function admin_screen_help ()
 - function admin_screen_logic ()
 - function move_admin_menu ()
 - function import ()
 - function export ()
 - function add_to_export_query ()
 - function add_single_to_export_query ()
 - function construct_database_query ()

 - Create $colabs_backup Object
-----------------------------------------------------------------------------------*/

class CoLabsThemes_Backup {
	
	var $admin_page;
	var $token;
	
	function CoLabsThemes_Backup () {
		$this->admin_page = '';
		$this->token = 'colabsthemes-backup';
	} // End Constructor
	
	/**
	 * init()
	 *
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	
	function init () {
		if ( is_admin() && ( get_option( 'framework_colabs_backupmenu_disable' ) != 'true' ) ) {
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
			
		$this->admin_page = add_submenu_page('colabsthemes', __( 'ColorLabs Import/Export Settings', 'colabsthemes' ), __( 'Import/Export Settings', 'colabsthemes' ), 'manage_options', $this->token, array( &$this, 'admin_screen' ) );
			
		// Admin screen logic.
		add_action( 'load-' . $this->admin_page, array( &$this, 'admin_screen_logic' ) );
		
		// Add contextual help.
		add_action( 'contextual_help', array( &$this, 'admin_screen_help' ), 10, 3 );
				
		add_action( 'admin_notices', array( &$this, 'admin_notices' ), 10 );
        
        // Register admin head on backup page
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

<div id="colabs_options" class="wrap <?php if (is_rtl()) { echo 'rtl'; } ?> colabs_backup">

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
    
    	<h3 class="heading"><?php _e( 'Import Settings', 'colabsthemes' ); ?></h3>
    	<div class="option">
    	<p><?php _e( 'If you have settings in a backup file on your computer, the ColorLabs Framework can import those into this site. To get started, upload your backup file to import from below.', 'colabsthemes' ); ?></p>
    
    	<div class="form-wrap">
    		<form enctype="multipart/form-data" method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->token ); ?>">
    			<?php wp_nonce_field( 'colabsthemes-backup-import' ); ?>
    			<label for="colabsthemes-import-file"><?php printf( __( 'Upload File: (Maximum Size: %s)', 'colabsthemes' ), ini_get( 'post_max_size' ) ); ?></label>
    			<input type="file" id="colabsthemes-import-file" name="colabsthemes-import-file" size="25" />
    			<input type="hidden" name="colabsthemes-backup-import" value="1" />
    			<input type="submit" class="button" value="<?php _e( 'Upload File and Import', 'colabsthemes' ); ?>" />
    		</form>
    	</div><!--/.form-wrap-->
    	</div><!-- .option -->

    </div><!-- .section -->
    
    <div class="section">
            
    	<h3 class="heading"><?php _e( 'Export Settings', 'colabsthemes' ); ?></h3>
    	<div class="option">
    	<p><?php _e( 'When you click the button below, the ColorLabs Framework will create a text file for you to save to your computer.', 'colabsthemes' ); ?></p>
    	<p><?php echo sprintf( __( 'This text file can be used to restore your settings here on "%s", or to easily setup another website with the same settings".', 'colabsthemes' ), get_bloginfo( 'name' ) ); ?></p>
    		
    	<form method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->token ); ?>">
    		<?php wp_nonce_field( 'colabsthemes-backup-export' ); ?>
    		<p><label><input type="radio" name="export-type" value="all"<?php checked( 'all', $export_type ); ?>> <?php _e( 'All Settings', 'colabsthemes' ); ?></label>
            <span class="description"><?php _e( 'This will contain all of the options listed below.', 'colabsthemes' ); ?></span></p>
    
    		<p><label for="content"><input type="radio" name="export-type" value="theme"<?php checked( 'theme', $export_type ); ?>/> <?php _e( 'Theme Options', 'colabsthemes' ); ?></label></p>
    		
    		<p><label for="content"><input type="radio" name="export-type" value="seo"<?php checked( 'seo', $export_type ); ?>/> <?php _e( 'SEO Settings', 'colabsthemes' ); ?></label></p>
    		
    		<?php if(get_option('colabs_themename')=='Backbone'){ ?><p><label for="content"><input type="radio" name="export-type" value="sidebar"<?php checked( 'sidebar', $export_type ); ?>/> <?php _e( 'Sidebar Manager', 'colabsthemes' ); ?> <span class="description"><?php _e( 'This will contain only the custom sidebars themselves and not the widgets within them', 'colabsthemes' ); ?></span></label></p><?php } ?>
    		
    		<input type="hidden" name="colabsthemes-backup-export" value="1" />
    		<input type="submit" class="button" value="<?php _e( 'Download Export File', 'colabsthemes' ); ?>" />
    	</form>
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
		  '<h3>' . __( 'Welcome to the ColorLabs Import/Export Manager.', 'colabsthemes' ) . '</h3>' .
		  '<p>' . __( 'Here are a few notes on using this screen.', 'colabsthemes' ) . '</p>' .
		  '<p>' . __( 'The backup manager allows you to backup or restore your "Theme Options" and other settings to or from a text file.', 'colabsthemes' ) . '</p>' .
		  '<p>' . __( 'To create a backup, simply select the setting type you\'d like to backup (or "All Settings") and hit the "Download Export File" button.', 'colabsthemes' ) . '</p>' .
		  '<p>' . __( 'To restore your settings from a backup, browse your computer for the file (under the "Import Settings" heading) and hit the "Upload File and Import" button. This will restore only the settings that have changed since the backup.', 'colabsthemes' ) . '</p>' .
		  
		  '<p><strong>' . __( 'Please note that only valid backup files generated through the ColorLabs Import/Export Manager should be imported.', 'colabsthemes' ) . '</strong></p>' .

		  '<p><strong>' . __( 'Looking for assistance?', 'colabsthemes' ) . '</strong></p>' .
		  '<p>' . sprintf( __( 'Please post your query on the %sColorLabs Support Forums%s where we will do our best to assist you further.', 'colabsthemes' ), '<a href="http://colorlabsproject.com/support/" target="_blank">', '</a>' ) . '</p>';
		
		} // End IF Statement
		
		return $contextual_help;
	
	} // End admin_screen_help()
	
	/**
	 * admin_notices()
	 *
	 * Display admin notices when performing backup/restore.
	 *
	 * @since 1.0.0
	 */
	
	function admin_notices () {
	
		if ( ! isset( $_GET['page'] ) || ( $_GET['page'] != $this->token ) ) { return; }
	
		echo '<div id="import-notice" class="updated"><p>' . sprintf( __( 'Please note that this backup manager backs up only your settings and not your content. To backup your content, please use the %sWordPress Export Tool%s.', 'colabsthemes' ), '<a href="' . admin_url( 'export.php' ) . '">', '</a>' ) . '</p></div><!--/#import-notice .message-->' . "\n";
			
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
	 * The processing code to generate the backup or restore from a previous backup.
	 *
	 * @since 1.0.0
	 */
	
	function admin_screen_logic () {
		
		if ( ! isset( $_POST['colabsthemes-backup-export'] ) && isset( $_POST['colabsthemes-backup-import'] ) && ( $_POST['colabsthemes-backup-import'] == true ) ) {
			$this->import();
		}
		
		if ( ! isset( $_POST['colabsthemes-backup-import'] ) && isset( $_POST['colabsthemes-backup-export'] ) && ( $_POST['colabsthemes-backup-export'] == true ) ) {
			$this->export();
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
			if ( in_array( $s[2], array( 'colabsthemes-backup' ) ) ) {
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
		check_admin_referer( 'colabsthemes-backup-import' ); // Security check.
		
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
		if ( ! isset( $options['colabsthemes-backup-validator'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&invalid=true' ) );
			exit;
		} else {
			unset( $options['colabsthemes-backup-validator'] ); // Now that we've checked it, we don't need the field anymore.
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
	
	/**
	 * export()
	 *
	 * Export settings to a backup file.
	 *
	 * @since 1.0.0
	 * @uses global $wpdb
	 */
	 
	function export() {
		global $wpdb;
		check_admin_referer( 'colabsthemes-backup-export' ); // Security check.
		
		$export_options = array( 'all', 'theme', 'seo', 'sidebar', 'framework' );		
		
		if ( ! in_array( strip_tags( $_POST['export-type'] ), $export_options ) ) { return; } // No invalid exports, please.
		
		$export_type = esc_attr( strip_tags( $_POST['export-type'] ) );
		
		$settings = array();
		
		$query = $this->construct_database_query( $export_type );
		
		// Error trapping for the export.
		if ( '' == $query ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&error-export=true' ) );
			return;
		}
		
		// If we get to this stage, all is safe so run the query.
		$results = $wpdb->get_results( $query );

		foreach ( $results as $result ) {
		
		     $settings[$result->option_name] = $result->option_value;
		
		} // End FOREACH Loop
		
		// Remove the "blogname" and "blogdescription" fields
		unset( $settings['blogname'] );
		unset( $settings['blogdescription'] );
		
		if ( ! $settings ) { return; }
	
		// Add our custom marker, to ensure only valid files are imported successfully.
		$settings['colabsthemes-backup-validator'] = date( 'Y-m-d h:i:s' );
	
		// Generate the export file.
	    $output = json_encode( (array)$settings );
	
	    header( 'Content-Description: File Transfer' );
	    header( 'Cache-Control: public, must-revalidate' );
	    header( 'Pragma: hack' );
	    header( 'Content-Type: text/plain' );
	    header( 'Content-Disposition: attachment; filename="' . $this->token . '-' . date( 'Ymd-His' ) . '.json"' );
	    header( 'Content-Length: ' . strlen( $output ) );
	    echo $output;
	    exit;
			
	} // End export()
	
	/**
	 * add_to_export_query()
	 *
	 * Loop through an array of options and add them to the MySQL SELECT query string.
	 *
	 * @since 1.0.0
	 * @param $options array
	 * @param $count int
	 * @return $query array ( string, count )
	 */
	 
	function add_to_export_query ( $options, $count ) {
		$query = array();
		$query_inner = '';
		
		foreach( $options as $option ) {

			if( isset( $option['id'] ) ) {
				$count++;
				$option_id = $option['id'];
				
				$option_id = esc_attr( $option_id );
				$option_id = sanitize_title( $option_id );

				if( $count > 1 ) { $query_inner .= ' OR '; }
				$query_inner .= "option_name = '$option_id'";

				// Width/Height-type fields
				if ( is_array( $option['type'] ) ) {
					foreach ( $option['type'] as $o ) {
						if( $count > 1 ){ $query_inner .= ' OR '; }
						if ( isset( $o['id'] ) ) {
							$option_id = $o['id'];
							
							$option_id = esc_attr( $option_id );
							$option_id = sanitize_title( $option_id );	
							
							$query_inner .= "option_name = '$option_id'";
						}
					}
				}
				
				// Multicheck fields
				if ( ! is_array( $option['type'] ) && ( 'multicheck' == $option['type'] || 'multicheck2' == $option['type'] ) ) {
					foreach ( $option['options'] as $k => $v ) {
						if ( ! is_numeric( $k ) ) {
							$option_id = $option['id'] . '_' . $k;
							
							$option_id = esc_attr( $option_id );
							$option_id = sanitize_title( $option_id );
							
						}
					}
				}
                
			}
		}
		
		$query['string'] = $query_inner;
		$query['count'] = $count;
		
		return $query;
	} // End add_to_export_query()

	/**
	 * add_single_to_export_query()
	 *
	 * Add a single item to the MySQL SELECT query string.
	 *
	 * @since 1.0.0
	 * @param $option_id string
	 * @param $count int
	 * @return $query array ( string, count )
	 */
	 
	function add_single_to_export_query ( $option_id, $count ) {
		$query = array();
		$query_inner = '';
		
		$option_id = esc_attr( $option_id );
		$option_id = sanitize_title( $option_id );
		
		if( $count > 1 ) { $query_inner .= ' OR '; }
		$query_inner .= "option_name = '$option_id'";
		
		$query['string'] = $query_inner;
		$query['count'] = $count;
		
		return $query;
	} // End add_single_to_export_query()
	
	/**
	 * construct_database_query()
	 *
	 * Constructs the database query based on the export type.
	 *
	 * @since 1.0.0
	 * @param $export_type string
	 * @uses global $wpdb
	 */
	
	function construct_database_query ( $export_type ) {
		global $wpdb;
		
		$query = '';
		$query_inner = '';
		$count = 0;
	
		// Begin populating settings to be exported.
		switch ( $export_type ) {
		
			// All Settings
			case 'all':
				
				// Theme Options
				$options = get_option( 'colabs_template' );
				
				if ( is_array( $options ) ) {
					$query = $this->add_to_export_query( $options, $count );
					
					$query_inner .= $query['string'];
					$count = $query['count'];
				}
				
				// SEO Settings
				$options = get_option( 'colabs_seo_template' );
				
				if ( is_array( $options ) ) {
					$query = $this->add_to_export_query( $options, $count );
					
					$query_inner .= $query['string'];
					$count = $query['count'];
				}
				
				// Sidebar Manager
				
				$option_id = 'sbm_colabs_sbm_options';
				
				$query = $this->add_single_to_export_query( $option_id, $count );
				
				$query_inner .= $query['string'];
				$count = $query['count'];
				
				// Framework Settings
				$options = get_option( 'colabs_framework_template' );
				
				if ( is_array( $options ) ) {
					// Remove the "framework_colabs_export_options" and "framework_colabs_import_options" items before constructing the query.
					foreach ( (array) $options as $k => $v ) {
						if ( isset( $options[$k]['id'] ) && in_array( $options[$k]['id'], array( 'framework_colabs_import_options', 'framework_colabs_export_options' ) ) ) {
							unset( $options[$k] );
						}
					}
					
					$query = $this->add_to_export_query( $options, $count );
					
					$query_inner .= $query['string'];
					$count = $query['count'];
				}
			break;
		
			// Theme Options
			case 'theme':
			
                $options_theme = get_option( 'colabs_template' );
    
                $options_layout = get_option( 'colabs_layout_template' );
                
                if( !empty($options_layout) ){ $options = array_merge($options_theme,$options_layout); 
                }else{ $options = get_option( 'colabs_template' ); }
				
				if ( is_array( $options ) ) {
					$query = $this->add_to_export_query( $options, $count );
					
					$query_inner .= $query['string'];
					$count = $query['count'];
				}
			
			break;
			
			// SEO Settings
			case 'seo':
			
				$options = get_option( 'colabs_seo_template' );
				
				if ( is_array( $options ) ) {
					$query = $this->add_to_export_query( $options, $count );
					
					$query_inner .= $query['string'];
					$count = $query['count'];
				}
			
			break;
			
			// Sidebar Manager
			case 'sidebar':
			
				$option_id = 'sbm_colabs_sbm_options';
				
				$query = $this->add_single_to_export_query( $option_id, $count );
				
				$query_inner .= $query['string'];
				$count = $query['count'];
			
			break;
			
			// Framework Settings
			case 'framework':
			
				$options = get_option( 'colabs_framework_template' );
				
				if ( is_array( $options ) ) {
					// Remove the "framework_colabs_export_options" and "framework_colabs_import_options" items before constructing the query.
					foreach ( (array) $options as $k => $v ) {
						if ( isset( $options[$k]['id'] ) && in_array( $options[$k]['id'], array( 'framework_colabs_import_options', 'framework_colabs_export_options' ) ) ) {
							unset( $options[$k] );
						}
					}
					
					$query = $this->add_to_export_query( $options, $count );
					
					$query_inner .= $query['string'];
					$count = $query['count'];
				}
			
			break;
		}
		
		// Allow child themes/plugins to add their own data to the exporter.
		$query_inner = apply_filters( 'colabsframework_export_query_inner', $query_inner );
		
		if ( $query_inner != '' ) {
			$query = 'SELECT option_name, option_value FROM ' . $wpdb->options . ' WHERE ' . $query_inner;
		}
		
		return $query;
	
	} // End construct_database_query()
} // End Class

/**
 * Create $colabs_backup Object.
 *
 * @since 1.0.0
 * @uses CoLabsThemes_Backup
 */

$colabs_backup = new CoLabsThemes_Backup();
$colabs_backup->init();
?>