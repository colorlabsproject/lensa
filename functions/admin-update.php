<?php
/**-----------------------------------------------------------------------------------
 * ColorLabs Themes & Framework Updater
 *
 * @package CoLabsFramework
 * @since 1.0
TABLE OF CONTENTS
- Framework Updater
	- CoLabsFramework Update Page
 	- CoLabsFramework Update Head
 	- CoLabsFramework Version Getter
-----------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------*/
/* CoLabsFramework Update Page */
/*-----------------------------------------------------------------------------------*/
function colabsthemes_framework_update_page(){
	$themename =  get_option( 'colabs_themename' );
	global $message;
?>
<div class="wrap colabs_notice">
<div id="colabs_options" class="wrap<?php if (is_rtl()) { echo ' rtl'; } ?>">
	<div class="one_col wrap colabs_container">
		<?php echo $message;?>
	<div id="main">
	<div id="panel-header">
        <?php colabsthemes_options_page_header('save_button=false'); ?>
	</div><!-- #panel-header -->
    <div id="panel-content">
    <?php
    if(isset($_POST['password'])){
            $cred = $_POST;
            $filesystem = WP_Filesystem($cred);
		} elseif(isset($_POST['colabs_ftp_cred'])){
             $cred = unserialize(stripcslashes($_POST['colabs_ftp_cred']));
             $filesystem = WP_Filesystem($cred);				
    } else {
           $filesystem = WP_Filesystem();
    };
    $url = admin_url( 'admin.php?page=colabsthemes_framework_update' );
    
    if($filesystem == false){
      request_filesystem_credentials ( $url );
    } else {
		?>
		<div class="section">
			<h3 class="heading"><?php _e("Framework Update","colabsthemes"); ?></h3>
			<div class="option">
        <?php colabsthemes_framework_update_page_set(); ?>
			</div><!-- .option -->
    </div><!-- .section -->
		<div class="section">
			<h3 class="heading"><?php _e("Themes Update","colabsthemes"); ?></h3>
			<div class="option">
        <?php colabsthemes_themes_update_page_set(); ?>
			</div><!-- .option -->
    </div><!-- .section -->
		<?php }?>
    </div><!-- #panel-content -->
    <div id="panel-footer">
      <ul>
          <li class="docs"><a title="Theme Documentation" href="http://colorlabsproject.com/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" ><?php _e("View Documentation","colabsthemes"); ?></a></li>
          <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank"><?php _e("Submit a Support Ticket","colabsthemes"); ?></a></li>
          <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank"><?php _e("Suggest a Feature","colabsthemes"); ?></a></li>
      </ul>
  	</div><!-- #panel-footer -->
	</div><!-- #main -->
	</div><!-- .colabs_container -->
</div><!-- #colabs_options -->
</div><!-- .wrap -->
<?php
} //end of colabsthemes_framework_update_page
function colabsthemes_framework_update_page_set(){
	if(isset($_POST['password'])){
            $cred = $_POST;
            $filesystem = WP_Filesystem($cred);
        }
        elseif(isset($_POST['colabs_ftp_cred'])){
             $cred = unserialize(stripcslashes($_POST['colabs_ftp_cred']));
             $filesystem = WP_Filesystem($cred);
        } else {
           $filesystem = WP_Filesystem();
        };
  $localversion = get_option( 'colabs_framework_version' );
  $remoteversion = colabs_get_fw_version();
  $upd = colabsthemes_framework_update_check();
  ?>
  <span style="display:none"><?php echo $method; ?></span>
  <form method="post"  enctype="multipart/form-data" id="colabsform" action="<?php /* echo $url; */ ?>">
    <?php if( $upd['update'] ) { ?>
      <?php wp_nonce_field( 'update-options' ); ?>
      <h3><?php _e("A new version of ColorLabs Framework is available.","colabsthemes"); ?></h3>
      <p><?php _e("This updater will download and extract the latest ColorLabs Framework files to your current theme's functions folder. ","colabsthemes"); ?></p>
      <p><?php _e("We recommend backing up your theme files before updating.","colabsthemes"); ?></p>
      <p>&rarr; <strong><?php _e("Your version:","colabsthemes"); ?></strong> <?php echo $upd['localversion']; ?></p>
      <p>&rarr; <strong><?php _e("New Version:","colabsthemes"); ?></strong> <?php echo $upd['remoteversion']; ?></p>
      <input type="submit" class="button" value="Update Framework" />
			<input type="hidden" name="colabs_update_save" value="save" />
			<input type="hidden" name="colabs_ftp_cred" value="<?php echo esc_attr(serialize($_POST)); ?>" />
    <?php } else { ?>
      <h3><?php _e("You have the latest version of ColorLabs Framework","colabsthemes"); ?></h3>
			<p>&rarr; <strong><?php _e("Your version:","colabsthemes"); ?></strong> <?php echo $upd['localversion']; ?></p>
    <?php } ?>
    
  </form>
  <?php 
};
function colabsthemes_themes_update_page_set(){
	global $wp_filesystem, $message;
	$context = get_theme_root();
	$target_dir = $wp_filesystem->find_folder($context);
	$target_file = trailingslashit($target_dir).'cookie.txt';
	$cookie_content = $wp_filesystem->get_contents( $target_file );
	$check_cookie=extractCookies($cookie_content);
	
	if ($check_cookie!=true){
		if( isset($_POST['login_attempt_id']) && $_POST['login_attempt_id']=='1342424497'){
			_e('<div id="colabs-no-archive-warning" class="updated fade" style="display:block;"><p><strong><i>The user name or password is incorrect</i></strong></p></div>','colabsthemes');
		}?>
		<p><?php _e('Please login with your ColorLabs account before updating your theme.','colabsthemes');?></p>
		<form method="post"  enctype="multipart/form-data" id="colabsform" name="login" class="colabs-login-form">
			<p>
				<label class="element-title" for="login"><?php _e('E-Mail Address or Username:','colabsthemes');?></label> 
				<input id="login" name="amember_login" size="15" value="" type="text">
			</p>
			<p>
				<label class="element-title" for="pass"><?php _e('Password:','colabsthemes');?></label> 
				<input id="pass" name="amember_pass" size="15" type="password">
			</p>
			<p style="margin-top: 25px;">
			<input type="submit" name="colabs_theme_login" value="Log In" class="button" />
			<input type="hidden" value="true" name="member_login">	
			<input type="hidden" name="colabs_ftp_cred" value="<?php echo esc_attr(serialize($_POST)); ?>" />
			</p>			
		</form>
		<p><?php _e('<a href="http://colorlabsproject.com/member/member/#am-forgot-block" target="_blank">Forgot Password?</a>','colabsthemes');?></p>
    <?php }else{
		$theme_name = get_option( 'colabs_themename' );
		$storefront_theme = colabs_get_fw_version('http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt'); 
		$check_theme_update = version_compare( $storefront_theme, COLABS_THEME_VER, '>' );
		$details_url = add_query_arg(array('TB_iframe' => 'true', 'width' => 1024, 'height' => 800), 'http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt');
	
		$backup = esc_url( add_query_arg(array( 'page' => 'colabsthemes_framework_update','theme_backup'=>'true' )) );
		if($check_theme_update==1){
			printf( __('<h3>An updated version of %1$s is available.</h3>','colabsthemes'), $theme_name );
			printf( __('<p>You can update to <a href="%3$s" class="thickbox">%1$s %2$s</a> automatically.</p><p> To use the automatic update and backup feature, cURL must be enabled on your hosting. If cURL is disabled, please contact your hosting.</p><p>Updating this theme will lose any customizations you have made. We recommend backing up your theme files before updating.</p><p>Please backup your theme by clicking the Backup button before updating your theme. Backup (.zip) will be stored in <code>wp-content/themes/</code>.</p>'), $theme_name,$storefront_theme, $details_url );

			?>
			
			<form method="post"  enctype="multipart/form-data" id="colabsform" name="update" class="colabs-update-form">
			<input type="submit" name="colabs_theme_update" value="Update" class="button" />
			<input type="hidden" value="true" name="colabs-upgrade-theme">
			<input type="hidden" name="colabs_ftp_cred" value="<?php echo esc_attr(serialize($_POST)); ?>" />
			</form>
			<form method="post"  enctype="multipart/form-data" id="colabsform" name="backup" class="colabs-backup-form">
				<input type="submit" name="colabs_theme_backup" value="Backup" class="button" />
				<input type="hidden" value="true" name="theme_backup">
				<input type="hidden" name="colabs_ftp_cred" value="<?php echo esc_attr(serialize($_POST)); ?>" />
			</form>
		
			<?php
		}else{
			printf( __('<h3>You have the latest version of %1$s.</h3><p>&rarr; <a href="%2$s" class="thickbox" title="%1$s">View version %3$s details</a></p>'), $theme_name, $details_url, $storefront_theme );
			printf( __('<p>Click the Backup button to back up your theme files. Backup (.zip) will be stored in <code>wp-content/themes/</code></p>'), $backup );
			?>
			<p style="margin-top: 30px;">
			<form method="post"  enctype="multipart/form-data" id="colabsform" name="login" class="colabs-login-form">
				<input type="submit" name="colabs_theme_backup" value="Backup" class="button" />
				<input type="hidden" value="true" name="theme_backup">
				<input type="hidden" name="colabs_ftp_cred" value="<?php echo esc_attr(serialize($_POST)); ?>" />
			</form>
			</p>
			<?php
		}
	}
}
function colabsthemes_framework_update_check(){
    $data = array( 'update' => false, 'version' => '1.0.0', 'status' => 'none' );
    $data['localversion'] = get_option( 'colabs_framework_version' );
    
    $theme = wp_get_theme();
    if('backbone'==$theme->Template){
    $data['remoteversion'] = colabs_get_fw_version('http://colorlabsproject.com/updates/functions-changelog.txt');
    }else{
    	$data['remoteversion'] = colabs_get_fw_version();
    }

	if ( ! $data['localversion'] ) { return $data; }
    $check = version_compare( $data['remoteversion'], $data['localversion'] ); // Returns 1 if there is an update available.
	if ( $check == 1 ) {
		$data['update'] = true;
        $data['version'] = $data['version'];
	}
	return $data;
}
/*-----------------------------------------------------------------------------------*/
/* CoLabsFramework Update Head */
/*-----------------------------------------------------------------------------------*/
function colabsthemes_framework_update_head(){
	global $message;
  if(isset($_REQUEST['page'])){
	// Sanitize page being requested.
	$_page = strtolower( strip_tags( trim( $_REQUEST['page'] ) ) );
	if( 'colabsthemes_framework_update' == $_page){
		//Setup Filesystem
		$method = get_filesystem_method();
		if(isset($_POST['colabs_ftp_cred'])){
			$cred = unserialize(stripcslashes($_POST['colabs_ftp_cred']));
			$filesystem = WP_Filesystem($cred);
		} else {
		   $filesystem = WP_Filesystem();
		};
	if(isset($_REQUEST['colabs_update_save'])){
			// Sanitize action being requested.
			$_action = strtolower( trim( strip_tags( $_REQUEST['colabs_update_save'] ) ) );
		if( 'save' == $_action ){
		$temp_file_addr = download_url( 'http://colorlabsproject.com/updates/framework.zip' );
		if ( is_wp_error($temp_file_addr) ) {
			$error = $temp_file_addr->get_error_code();
			if('http_no_url' == $error) {
			//The source file was not found or is invalid
				$message = "<div id='source-warning' class='updated fade'><p>". __("Failed: Invalid URL Provided","colabsthemes")."</p></div>";
			} else {
					$message = "<div id='source-warning' class='updated fade'><p>". __("Failed: Upload","colabsthemes")." - $error</p></div>";
			}
			return;
		  }
		//Unzipp it
		global $wp_filesystem;
		$to = $wp_filesystem->wp_content_dir() . "/themes/" . get_option( 'template') . "/functions/";
		$dounzip = unzip_file($temp_file_addr, $to);
		unlink($temp_file_addr); // Delete Temp File
		if ( is_wp_error($dounzip) ) {
			//DEBUG
			$error = $dounzip->get_error_code();
			if('incompatible_archive' == $error) {
				//The source file was not found or is invalid
					$message = "<div id='colabs-no-archive-warning' class='updated fade'><p>". __("Failed: Incompatible archive","colabsthemes")."</p></div>";
			}
			if('empty_archive' == $error) {
					$message = "<div id='colabs-empty-archive-warning' class='updated fade'><p>". __("Failed: Empty Archive","colabsthemes")."</p></div>";
			}
			if('mkdir_failed' == $error) {
					$message = "<div id='colabs-mkdir-warning' class='updated fade'><p>". __("Failed: mkdir Failure","colabsthemes")."</p></div>";
			}
			if('copy_failed' == $error) {
					$message = "<div id='colabs-copy-fail-warning' class='updated fade'><p>". __("Failed: Copy Failed","colabsthemes")."</p></div>";
			}
			return;
		}

		$message = "<div id='framework-upgraded' class='updated fade'><p>". __("New framework successfully downloaded, extracted and updated.","colabsthemes")."</p></div>";
		}
	}
	} //End user input save part of the update
 }
}
add_action( 'admin_head','colabsthemes_framework_update_head' );
//Updater Load Scripts
if (!function_exists('colabs_load_only_updater')) {
function colabs_load_only_updater(){
    add_action( 'admin_head', 'colabs_admin_head_editor' );
    function colabs_admin_head_editor(){
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/functions/admin-style.css" media="screen" />';        
    }//END function colabs_admin_head_editor
}//END function colabs_load_only_updater
}//END function_exists
/*-----------------------------------------------------------------------------------*/
/* CoLabsFramework Version Getter */
/*-----------------------------------------------------------------------------------*/
function colabs_get_fw_version($url = ''){
    $output = '';
	if(!empty($url)){
		$fw_url = $url;
	} else {
    	$fw_url = 'http://colorlabsproject.com/updates/functions-changelog.txt';
    }
    if(empty($fw_url)) return;
	$temp_file_addr = download_url($fw_url);
	if(!is_wp_error($temp_file_addr) && $file_contents = file($temp_file_addr)) {
        foreach ($file_contents as $line_num => $line) {
                $current_line =  $line;
                if($line_num > 1){    // Not the first or second... dodgy :P
                    if (preg_match( '/^[0-9]/', $line)) {
                            $current_line = stristr($current_line,"Version" );
                            $current_line = preg_replace( '~[^0-9,.]~','',$current_line);
                            $output = $current_line;
                            break;
                    }
                }
        }
        unlink($temp_file_addr);
        return $output;
    } else {
        return __("Currently Unavailable","colabsthemes");
    }
}
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Update Check Function - colabs_theme_check */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_theme_check')) {
function colabs_theme_check(){
	$theme_name = get_option( 'colabs_themename' );
	$storefront_theme = colabs_get_fw_version('http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt'); 
	$check_theme_update = version_compare( $storefront_theme, COLABS_THEME_VER, '>' );
	$details_url = add_query_arg(array('TB_iframe' => 'true', 'width' => 1024, 'height' => 800), 'http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt');
	$update_url = esc_url( add_query_arg(array( 'page' => 'colabsthemes_framework_update' ) ) );
	if($check_theme_update==1){
	?>
	<div class="colabs-save-popup" id="colabs-update-theme" style="display:block;">
		<div class="colabs-save-save">
			<?php 
			printf( __('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s details</a> or <a href="%4$s" >update now</a>.'), $theme_name, $details_url, $storefront_theme, $update_url);
			?>
		</div>
	</div>
	<?php
	}
}
}
if (!function_exists( 'colabs_theme_update')) {
function colabs_theme_update(){
  if(isset($_REQUEST['page'])){
	// Sanitize page being requested.
	$_page = strtolower( strip_tags( trim( $_REQUEST['page'] ) ) );
	if( 'colabsthemes_framework_update' == $_page){

	//Setup Filesystem
	global $wp_filesystem, $message;
	if(isset($_POST['password'])){
	  $cred = $_POST;
	  $filesystem = WP_Filesystem($cred);
	} else if(isset($_POST['colabs_ftp_cred'])){
			$cred = unserialize(stripcslashes($_POST['colabs_ftp_cred']));
			$filesystem = WP_Filesystem($cred);	
	} else{
		$filesystem = WP_Filesystem();
	}
	$theme_name = get_option( 'colabs_themename' );
	$file_url = 'http://colorlabsproject.com/member/downloads/'.strtolower($theme_name).'/'.strtolower($theme_name).'.zip';
	$context = get_theme_root();
	$target_dir = $wp_filesystem->find_folder($context);
	$target_file = trailingslashit($target_dir).'cookie.txt';
	$tmpfname = wp_tempnam($file_url);
	
	// Get Cookie
	// ----------	
	if( isset($_POST['member_login']) && $_POST['member_login']=='true'){
			
			$response = wp_remote_post(
				"http://colorlabsproject.com/member/login.php",
				array(
					'timeout' => 30,
					'redirection' => 0,
					'headers' => array(),
					'body' => array(
						'amember_login' => $_POST['amember_login'],
						'amember_pass'  => $_POST['amember_pass']
					)
			    )
			);
			//Was there some error connecting to the server?
			if( is_wp_error( $response ) ) {
				$errorCode = $response->get_error_code();
				$message = 'Error: ' . $errorCode;
				die();
			}else{
				$cookies = '';
				if(isset($response['cookies'])){
						$cookies = serialize($response['cookies']);
					if(!$wp_filesystem->put_contents($target_file, $cookies, FS_CHMOD_FILE)){
						$message = 'Error when writing file'; //return error object
					} 
				}
			}
	}
	// Download files
	// --------------
	$action = isset($_REQUEST['colabs-upgrade-theme']) ? $_REQUEST['colabs-upgrade-theme'] : 'false';
	
	if ( ! current_user_can('update_themes') )
			wp_die(__('You do not have sufficient permissions to update themes for this site.','colabsthemes'));
	if ( 'true' == $action ) {
		$cookie_content = $wp_filesystem->get_contents( $target_file );
		$check_cookie=extractCookies($cookie_content);
		if ($check_cookie==true){
			$cookie_file = unserialize($wp_filesystem->get_contents($target_file));
			$get_zip_file = wp_remote_get(
				$file_url,
				array(
					'timeout' => 30,
					'cookies' => $cookie_file,
					'stream' => true, 
					'filename' => $tmpfname
			    )
			);
			
			$do_unzip = unzip_file($get_zip_file['filename'], $target_dir);
			unlink($tmpfname);
			if ( is_wp_error($do_unzip) ) {
				$error = $do_unzip->get_error_code();
				$data = $do_unzip->get_error_data($error);
				if('incompatible_archive' == $error) {
				//The source file was not found or is invalid
					$signin = esc_url( add_query_arg(array( 'page' => 'colabsthemes_framework_update','relogin'=>'true', 'action' => '' )) );
					$message = "<div id='colabs-no-archive-warning' class='updated fade' ><p>"; 
					$message .= sprintf(__("This account is ineligible for the update. Please <a href='http://colorlabsproject.com/member/signup' target='_blank'>renew your subscription</a> or <a href='%s'>sign in</a> with a different account.","colabsthemes"),$signin);
					$message .= "</p></div>";
				}
				if('empty_archive' == $error) {
						$message = "<div id='colabs-empty-archive-warning' class='updated fade' ><p>". __("Failed: Empty Archive","colabsthemes")."</p></div>";
				}
				if('mkdir_failed' == $error) {
						$message = "<div id='colabs-mkdir-warning' class='updated fade' ><p>". __("Failed: mkdir Failure","colabsthemes")."</p></div>";
				}
				if('copy_failed' == $error) {
						$message = "<div id='colabs-copy-fail-warning' class='updated fade'><p>". __("Failed: Copy Failed","colabsthemes")."</p></div>";
				}
				return;
			}else{
				$message = "<div id='colabs-no-archive-warning' class='updated fade'><p>". __("Update process sucessfully","colabsthemes")."</p></div>";
			}
		}
	}
	//Re-Login
	//---------
	$relogin = isset($_REQUEST['relogin']) ? $_REQUEST['relogin'] : 'false';
	if($relogin=='true'){	
			unlink($target_file); // Delete Cookie File 
	 }
	
	//Backup
	//---------
	$theme_backup = isset($_REQUEST['theme_backup']) ? $_REQUEST['theme_backup'] : 'false';
	if($theme_backup=='true'){
		$file = get_theme_root().'/backup_'.strtolower($theme_name).'_'.time().'.zip';
		$dirName = get_template_directory();
		$do_zipped = zipped_file($file,$dirName);
		if ( !is_wp_error($do_zipped) ) {
			$message = "<div id='colabs-no-archive-warning' class='updated fade'><p>". __("Backup process sucessfully","colabsthemes")."</p></div>";
		}else{
			$error = $do_zipped->get_error_code();

			if('empty_archive' == $error) {
				$message = "<div id='colabs-empty-archive-warning' class='updated fade' ><p>". __("Failed: Empty Archive","colabsthemes")."</p></div>";
			}
			if('mkdir_failed' == $error) {
				$message = "<div id='colabs-mkdir-warning' class='updated fade' ><p>". __("Failed: mkdir Failure","colabsthemes")."</p></div>";
			}
			if('copy_failed' == $error) {
				$message = "<div id='colabs-copy-fail-warning' class='updated fade'><p>". __("Failed: Copy Failed","colabsthemes")."</p></div>";
			}
			
		}
	
	}

	} // end if 'colabsthemes_framework_update' == $_page
	} // end if isset($_REQUEST['page'])
}
}
add_action( 'admin_head','colabs_theme_update' );
function extractCookies($string) {
    $member_cookie = false;
    if(preg_match( '/amember_nr/i', $string, $member)){
		$member_cookie = true;  
		}
    return $member_cookie;
}
function zipped_file($file,$dirName){
	$zip = new ZipArchive();
	$zip->open($file, ZipArchive::CREATE);
		if (!is_dir($dirName)) {
		throw new Exception('Directory ' . $dirName . ' does not exist');
		}
		$dirName = realpath($dirName);
		if (substr($dirName, -1) != '/') {
		$dirName.= '/';
		}
		$dirStack = array($dirName);
		//Find the index where the last dir starts
		$cutFrom = strrpos(substr($dirName, 0, -1), '/')+1;
		$cutFrom = strrpos($dirName, '/')+1;
		while (!empty($dirStack)) {
		$currentDir = array_pop($dirStack);
		$filesToAdd = array();
		$dir = dir($currentDir);
		while (false !== ($node = $dir->read())) {
		if (('..' == $node) || ('.' == $node)) {
		continue;
		}
		if (is_dir($currentDir . $node)) {
		array_push($dirStack, $currentDir . $node . '/');
		}
		if (is_file($currentDir . $node)) {
		$filesToAdd[] = $node;
		}
		}
		$localDir = substr($currentDir, $cutFrom);
		$zip->addEmptyDir($localDir);
		foreach ($filesToAdd as $file) {
			$zip->addFile($currentDir . $file, $localDir . $file);
		}
		}
		$zip->close();
}
?>