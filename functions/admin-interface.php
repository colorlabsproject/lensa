<?php
// CoLabsThemes Admin Interface
/*-----------------------------------------------------------------------------------
TABLE OF CONTENTS
- CoLabsThemes Admin Interface - colabsthemes_add_admin
- CoLabsThemes Reset Function - colabs_reset_options
- Framework options panel - colabsthemes_options_page
- colabs_load_only
- Ajax Save Action - colabs_ajax_callback
- Generates The Options - colabsthemes_machine
- CoLabsThemes Uploader - colabsthemes_uploader_function
- CoLabsThemes Theme Version Checker - colabsthemes_version_checker
- CoLabsThemes Thumb Detection Notice - colabs_thumb_admin_notice
- CoLabsThemes Theme Update Admin Notice - colabs_theme_update_notice
-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'colabs_update_options_filter' ) ) {
  function colabs_update_options_filter( $new_value, $old_value ) {
    if ( !current_user_can( 'unfiltered_html' ) ) {
      // Options that get KSES'd
      foreach( colabs_ksesed_option_keys() as $option ) {
        $new_value[$option] = wp_kses_post( $new_value[$option] );
      }
      trigger_error( print_r( $new_value, true ) );
      // Options that cannot be set without unfiltered HTML
      foreach( colabs_disabled_if_not_unfiltered_html_option_keys() as $option ) {
        $new_value[$option] = $old_value[$option];
      }
    }
    return $new_value;
  }
}

if ( ! function_exists( 'colabs_prevent_option_update' ) ) {
  function colabs_prevent_option_update( $new_value, $old_value ) {
    return $old_value;
  }
}

/**
 * This is the list of options that are run through KSES on save for users without
 * the unfiltered_html capability
 */
if ( ! function_exists( 'colabs_ksesed_option_keys' ) ) {
  function colabs_ksesed_option_keys() {
    return array();
  }
}

/**
 * This is the list of standalone options that are run through KSES on save for users without
 * the unfiltered_html capability
 */
if ( ! function_exists( 'colabs_ksesed_standalone_options' ) ) {
  function colabs_ksesed_standalone_options() {
    return array( 'colabs_footer_left_text', 'colabs_footer_right_text', 'colabs_connect_content' );
  }
}

/**
 * This is the list of options that users without the unfiltered_html capability
 * are not able to update
 */
if ( ! function_exists( 'colabs_disabled_if_not_unfiltered_html_option_keys' ) ) {
  function colabs_disabled_if_not_unfiltered_html_option_keys() {
    return array( 'colabs_google_analytics', 'colabs_custom_css' );
  }
}

add_filter( 'pre_update_option_colabs_options', 'colabs_update_options_filter', 10, 2 );
foreach( colabs_ksesed_standalone_options() as $o ) {
  add_filter( 'pre_update_option_' . $o, 'wp_kses_post' );
}
unset( $o );

/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Admin Interface - colabsthemes_add_admin */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_add_admin')) {
function colabsthemes_add_admin() {
    global $query_string;
    global $current_user;
    $current_user_id = $current_user->user_login;
    $super_user = get_option( 'framework_colabs_super_user' );
    $themename =  get_option( 'colabs_themename' );
    $shortname =  get_option( 'colabs_shortname' );
    // Reset the settings, sanitizing the various requests made.
    // Use a SWITCH to determine which settings to update.
    /* Make sure we're making a request.
    ------------------------------------------------------------*/
    if ( isset( $_REQUEST['page'] ) ) {
      // Sanitize page being requested.
      $_page = '';
    $_page = mysql_real_escape_string( strtolower( trim( strip_tags( $_REQUEST['page'] ) ) ) );
    // Sanitize action being requested.
      $_action = '';
    if ( isset( $_REQUEST['colabs_save'] ) ) {
      $_action = mysql_real_escape_string( strtolower( trim( strip_tags( $_REQUEST['colabs_save'] ) ) ) );
    } // End IF Statement
    // If the action is "reset", run the SWITCH.
    /* Perform settings reset.
      ------------------------------------------------------------*/
    if ( 'reset' == $_action ) {
      // Add nonce security check.
      if ( function_exists( 'check_ajax_referer' ) ) {
        if ( 'colabsthemes_seo' == $_page ) {
          check_ajax_referer( 'colabsframework-seo-options-reset', '_ajax_nonce' );
        } else if ( 'colabsthemes_layout_settings' == $_page ) {
          check_ajax_referer( 'colabsframework-layout-options-reset', '_ajax_nonce' );
        } else {
          check_ajax_referer( 'colabsframework-theme-options-reset', '_ajax_nonce' );
        }
      } // End IF Statement     
      switch ( $_page ) {
        case 'colabsthemes':
          $options =  get_option( 'colabs_template' ); 
          colabs_reset_options($options,'colabsthemes_options' );
          header( "Location: admin.php?page=colabsthemes&reset=true" );
          die;
        break;
                
        case 'colabsthemes_framework_settings':

          $options = get_option( 'colabs_framework_template' );
          colabs_reset_options($options);
          header( "Location: admin.php?page=colabsthemes_framework_settings&reset=true" );
          die;

        break;

        case 'colabsthemes_layout_settings':

          $options = get_option( 'colabs_layout_template' );
          colabs_reset_options($options);
          header( "Location: admin.php?page=colabsthemes_layout_settings&reset=true" );
          die;

        break;
                
        case 'colabsthemes_seo':
          $options = get_option( 'colabs_seo_template' ); 
          colabs_reset_options($options);
          header( "Location: admin.php?page=colabsthemes_seo&reset=true" );
          die;
        break;
                
        case 'colabsthemes_sbm':

          delete_option( 'sbm_colabs_sbm_options' );
          header( "Location: admin.php?page=colabsthemes_sbm&reset=true" );
          die;

        break;
                
      } // End SWITCH Statement
    } // End IF Statement
    } // End IF Statement
    // Check all the Options, then if the no options are created for a relative sub-page... it's not created.
  if(get_option( 'framework_colabs_backend_icon')) { $icon = get_option( 'framework_colabs_backend_icon' ); }
  else { 
        $icon = trailingslashit( get_template_directory_uri() ) . 'images/logo16.png';
        if(!file_exists($icon)) $icon = get_template_directory_uri() . '/functions/images/colabs-icon.ico'; }

    if(function_exists( 'add_object_page'))
    {
        add_object_page ( 'Page Title', $themename, 'manage_options','colabsthemes','colabsthemes_options_page', $icon);
    }
    else
    {
        add_menu_page ( 'Page Title', $themename, 'manage_options','colabsthemes','colabsthemes_options_page', $icon);
    }
    
    // Add Theme Option Menu Item
    $colabspage = '';
    $colabspage = add_submenu_page( 'colabsthemes', 'Dashboard', 'Dashboard', 'manage_options', 'colabsthemes', 'colabsthemes_options_page' ); // Default

  // Add Layout Menu Item
  $colabslayout = '';
    $layoutfile = get_template_directory() . '/includes/theme-options-layout.php';
  if ( get_option( 'framework_colabs_layout_disable') != 'true' && file_exists($layoutfile) )
    $colabslayout = add_submenu_page( 'colabsthemes_dummy', 'Layout Settings', 'Layout Settings', 'manage_options', 'colabsthemes_layout_settings', 'colabsthemes_layout_settings_page' );

  // Add Sidebar Manager Menu Item
  $colabssbm = '';
  if ( get_option( 'framework_colabs_sbm_disable') != 'true' && 'Backbone' == get_option('colabs_themename') )
    $colabssbm = add_submenu_page( 'colabsthemes', 'Manage Sidebar', 'Manage Sidebar', 'manage_options', 'colabsthemes_sbm', 'colabsthemes_sbm_page' );
    
  // Add SEO Menu Item
  $colabsseo = '';
  if ( get_option( 'framework_colabs_seo_disable') != 'true' )
    $colabsseo = add_submenu_page( 'colabsthemes_dummy', 'SEO', 'SEO', 'manage_options', 'colabsthemes_seo', 'colabsthemes_seo_page' );

    // Add Custom Editor Menu Item
  $colabseditor = '';
  if ( get_option( 'framework_colabs_editor_disable') != 'true' && file_exists(COLABS_CUSTOM) )
    $colabseditor = add_submenu_page('colabsthemes', __('Custom File Editor', 'colabsthemes'), __('Custom File Editor', 'colabsthemes'), 'manage_options', 'colabsthemes_editor', array('colabs_custom_editor', 'options_page'));


  // Update Framework Menu Item
    if($super_user == $current_user_id || empty($super_user)) {
         $colabsthemepage = add_submenu_page( 'colabsthemes', 'CoLabsFramework Update', 'Updates', 'manage_options', 'colabsthemes_framework_update', 'colabsthemes_framework_update_page' );
    }

  // Add README.txt file submenu, if it exists
    $colabsthemes_readme_menu_pagehook = file_exists( get_template_directory() . '/README.txt' ) ? add_submenu_page('colabsthemes', __('Readme', 'colabsthemes'), __('Readme', 'colabsthemes'), 'edit_theme_options', 'readme', 'colabsthemes_readme_menu_admin') : null;

  // Add Dashboard Menu Item
    $colabsdash = '';
    $colabsdash = add_submenu_page( 'colabsthemes', 'Theme Info', 'Theme Info', 'manage_options', 'colabsthemes_info', 'colabsthemes_dashboard_page' );

  // Add framework functionaily to the head individually
    add_action( "admin_print_scripts-$colabsdash", 'colabs_admin_head_dashboard' );
    add_action( "admin_print_styles-$colabsdash", 'colabs_admin_head_dashboard' );
  add_action( "admin_print_scripts-$colabspage", 'colabs_load_only' );
  add_action( "admin_print_scripts-$colabslayout", 'colabs_load_only' );
  add_action( "admin_print_scripts-$colabsthemepage", 'colabs_load_only_updater' );        
  add_action( "admin_print_scripts-$colabseditor", 'colabs_load_only_editor' );
    add_action( "admin_print_scripts-$colabsthemes_readme_menu_pagehook", 'readme_register_admin_head' );
    
  add_action( "admin_print_scripts-$colabsseo", 'colabs_load_only' );
  add_action( "admin_print_scripts-$colabssbm", 'colabs_load_only' );
    
  // Add the non-JavaScript "save" to the load of each of the screens.
  add_action( "load-$colabspage", 'colabs_nonajax_callback' );
  add_action( "load-$colabslayout", 'colabs_nonajax_callback' );
  add_action( "load-$colabsseo", 'colabs_nonajax_callback' );

}
}

add_action( 'admin_menu', 'colabsthemes_add_admin' );

/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Reset Function - colabs_reset_options */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_reset_options')) {
function colabs_reset_options($options,$page = ''){
  $excludes = array( 'blogname' , 'blogdescription' );
  foreach($options as $option){
    if(isset($option['id'])){ 
      $option_id = $option['id'];
      $option_type = $option['type'];
      //Skip assigned id's
      if(in_array($option_id,$excludes)) { continue; }
      if('multicheck' == $option_type){
        foreach($option['options'] as $option_key => $option_option){
          $del = $option_id . "_" . $option_key;
          delete_option($del);
        }
      } else if(is_array($option_type)) {
        foreach($option_type as $inner_option){
          $option_id = $inner_option['id'];
          $del = $option_id;
          delete_option($option_id);
        }
      } else {
        delete_option($option_id);
      }
    }   
  } 
  //When Theme Options page is reset - Add the colabs_options option
  if( 'colabsthemes_options' == $page ){
    delete_option( 'colabs_options' );
  }
    if( 'colabsthemes_layout_settings' == $page ){
        delete_option('colabs_layout_options');
    }
}
}
/*-----------------------------------------------------------------------------------*/
/* Framework options panel - colabsthemes_options_page */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_options_page')) {
function colabsthemes_options_page(){
    $options =  get_option( 'colabs_template' );      
    $themename =  get_option( 'colabs_themename' );
    $shortname =  get_option( 'colabs_shortname' );
    $manualurl =  get_option( 'colabs_manual' );
    //Framework Version in Backend Header
    $colabs_framework_version = get_option( 'colabs_framework_version' );
    $theme_data = wp_get_theme();
    
    //GET themes update RSS feed and do magic
  include_once(ABSPATH . WPINC . '/feed.php' );
  $pos = strpos($manualurl, 'documentation' );
  $theme_slug = str_replace( "/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation
?>
<div class="wrap colabs_container">
    <form action="" enctype="multipart/form-data" id="colabsform">
    <?php
      // Add nonce for added security.
      if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-theme-options-update' ); } // End IF Statement
      $colabs_nonce = '';
      if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement
      if ( '' == $colabs_nonce ) {} else {
    ?>
      <input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
    <?php
      } // End IF Statement 
    ?>
        <div class="clear"></div>
        <?php colabs_theme_check();?>
        <div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save"><?php _e("Options Updated","colabsthemes"); ?></div></div>
        <div id="colabs-popup-reset" class="colabs-save-popup"><div class="colabs-save-reset"><?php _e("Options Reset","colabsthemes"); ?></div></div>
        <div style="width:100%;padding-top:15px;"></div>
        <div class="clear"></div>
        <?php 
    // Rev up the Options Machine
        $return = colabsthemes_machine($options);
        ?>

        <div id="main" class="menu-item-settings metabox-holder">
        
          <div id="panel-header">
                <?php colabsthemes_options_page_header( array( 'theme_data'=>$theme_data, 'themename'=>$themename ) ); ?>
          </div><!-- #panel-header -->

          <div id="sidebar-nav">
        <ul>
          <?php echo $return[1] ?>
        </ul>   
      </div>
      <div id="panel-content">
                <div class="group help-block"> <p><?php _e("Drag an icon on the left and drop it here to customize","colabsthemes"); ?></p> </div>
            <?php echo $return[0]; /* Settings */ ?>
            <div class="clear"></div>
          </div>
            
          <div id="panel-footer">
        <ul>
                    <li class="docs"><a title="Theme Documentation" href="http://colorlabsproject.com/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" ><?php _e("View Documentation","colabsthemes"); ?></a></li>
                    <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank"><?php _e("Submit a Support Ticket","colabsthemes"); ?></a></li>
                    <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank"><?php _e("Suggest a Feature","colabsthemes"); ?></a></li>
        </ul>
        
        <div class="save-bar save_bar_top right">
                <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-top" alt="Working..." />
          <input type="submit" value="Save Changes" class="button submit-button button-primary" />

          </form>
          <form action="<?php /*echo wp_specialchars( $_SERVER['REQUEST_URI'] )*/ ?>" method="post" style="display:inline" id="colabsform-reset">
              <?php
              // Add nonce for added security.
              if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-theme-options-reset' ); } // End IF Statement
              $colabs_nonce = '';
              if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-reset' ); } // End IF Statement
              if ( '' == $colabs_nonce ) {} else {
            ?>
              <input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
            <?php
              } // End IF Statement
            ?>
              <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button button-highlighted" onclick="return confirm( '<?php _e("Click OK to reset all options. All settings will be lost!","colabsthemes"); ?>' );" />
                  <input type="hidden" name="colabs_save" value="reset" /> 
              </form>
        </div>
      </div><!-- #panel-footer -->

        </div><!--/#main-->
        <div class="theme-info">
          <br>
      <span class="theme"><?php echo $themename; ?> <?php echo COLABS_THEME_VER; ?></span>
            <span class="framework"><?php _e( 'Framework', 'colabsthemes' ); ?> <?php echo $colabs_framework_version; ?></span>
        </div><!--/.theme-info-->

        <?php  if (!empty($update_message)) echo $update_message; ?>    
<div style="clear:both;"></div>    
</div><!--wrap-->
 <?php
}
}
/*-----------------------------------------------------------------------------------*/
/* colabs_load_only */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_load_only')) {
function colabs_load_only() {
  add_action( 'admin_head', 'colabs_admin_head' );

  wp_register_script( 'jquery-ui-datepicker', get_template_directory_uri() .'/functions/js/ui.datepicker.js', array( 'jquery-ui-core' ));
  wp_register_script( 'jquery-input-mask', get_template_directory_uri() .'/functions/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
  wp_register_script( 'colabs-scripts', get_template_directory_uri() .'/functions/js/colabs-scripts.js', array( 'jquery' ));

  wp_enqueue_script( 'jquery-ui-core' );
  wp_enqueue_script( 'jquery-ui-sortable' );
  wp_enqueue_script( 'jquery-ui-draggable' ); 
  wp_enqueue_script( 'jquery-ui-droppable' );
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_script( 'jquery-input-mask' );
    
  wp_enqueue_script( 'colabs-scripts' );
    
    // Register the typography preview JavaScript.
  wp_register_script( 'colabs-typography-preview', get_template_directory_uri() . '/functions/js/colabs-typography-preview.js', array( 'jquery' ), '1.0.0', true );
  wp_enqueue_script( 'colabs-typography-preview' );
}
}

/* colabs_admin_head()
--------------------------------------------------------------------------------*/
function colabs_admin_head() {
/* To change the CSS stylesheet depending on the chosen color */
global $_wp_admin_css_colors;

    wp_enqueue_style( 'colabs-admin-style', get_template_directory_uri() . '/functions/admin-style.css' );
    //wp_enqueue_style( 'jquery-ui-datepicker', get_template_directory_uri() . '/functions/css/jquery-ui-datepicker.css' );

if(isset($_wp_admin_css_colors['name'])){
    wp_enqueue_style( 'colabs-admin-'.$_wp_admin_css_colors['name'], get_template_directory_uri() . '/' . $_wp_admin_css_colors['name'] .'.css' );
}
    //COLOR Picker 
    $wp_version = get_bloginfo( 'version' );

    if (version_compare($wp_version, '3.4.0', '<')) {
        wp_enqueue_style( 'colabs-admin-colorpicker', get_template_directory_uri() . '/functions/css/colorpicker.css', array( 'colabs-admin-style' ) );
        wp_enqueue_script( 'colabs-admin-colorpicker-js', get_template_directory_uri() . '/functions/js/colorpicker.js' );
    }else{
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
    }
?>
  <script type="text/javascript" language="javascript">
  jQuery(document).ready(function(){
    //JQUERY DATEPICKER
    jQuery( '.colabs-input-calendar').each(function (){
      jQuery( '#' + jQuery(this).attr( 'id')).datepicker({showOn: 'button', buttonImage: '<?php echo get_template_directory_uri();?>/functions/images/calendar.gif', buttonImageOnly: true});
    });
    //JQUERY TIME INPUT MASK
    jQuery( '.colabs-input-time').each(function (){
      jQuery( '#' + jQuery(this).attr( 'id')).mask( "99:99" );
    });
    //Color Picker
    var bgImage = jQuery("#custom-background-image"),
      frame;
    <?php 
        $options = get_option( 'colabs_template' );
        //get layout options
        $layoutfile = get_template_directory() . '/includes/theme-options-layout.php';
        if( file_exists($layoutfile) ){
            $options = array_merge( get_option( 'colabs_layout_template'), $options );
        }
        //get editor options
        if( function_exists('colabs_options_editor') ){
            $options = array_merge( get_option( 'colabs_editor_template'), $options );
        }
    foreach($options as $option){
    if('color' == $option['type'] OR 'typography' == $option['type'] OR 'border' == $option['type']){
      if('typography' == $option['type'] OR 'border' == $option['type']){
        $option_id = $option['id'];
        $temp_color = get_option($option_id);
        $option_id = $option['id'] . '_color';
        $color = $temp_color['color'];
      }
      else {
        $option_id = $option['id'];
        $color = get_option($option_id);
      }
      
    if (version_compare($wp_version, '3.4.0', '>')) {
      ?>
        jQuery('#<?php echo $option_id; ?>').wpColorPicker({
          change: function( event, ui ) {
            bgImage.css('<?php echo $option_id; ?>_color', ui.color.toString());
          },
          clear: function() {
            bgImage.css('<?php echo $option_id; ?>_color', '');
          }
        });
        
        <?php }else{ ?>
        
       jQuery( '#<?php echo $option_id; ?>_picker').children( 'div').css( 'backgroundColor', '<?php echo esc_js( $color ); ?>' );    
       jQuery( '#<?php echo $option_id; ?>_picker').ColorPicker({
        color: '<?php echo esc_js( $color ); ?>',
        onShow: function (colpkr) {
          jQuery(colpkr).fadeIn(500);
          return false;
        },
        onHide: function (colpkr) {
          jQuery(colpkr).fadeOut(500);
          return false;
        },
        onChange: function (hsb, hex, rgb) {
          //jQuery(this).css( 'border','1px solid red' );
          jQuery( '#<?php echo $option_id; ?>_picker').children( 'div').css( 'backgroundColor', '#' + hex);
          jQuery( '#<?php echo $option_id; ?>_picker').next( 'input').attr( 'value','#' + hex);
        }
        });
      <?php }
      
      } } ?>
  });
  </script> 
  <?php
  //AJAX Upload
  ?>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/functions/js/ajaxupload.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function(){
    var flip = 0;
    jQuery( '#expand_options').click(function(){
      if(flip == 0){
        flip = 1;
        jQuery( '.colabs_container #colabs-nav').hide();
        jQuery( '.colabs_container #content').width(785);
        jQuery( '.colabs_container .group').add( '.colabs_container .group h2').show();
        jQuery(this).text( '[-]' );
      } else {
        flip = 0;
        jQuery( '.colabs_container #colabs-nav').show();
        jQuery( '.colabs_container #content').width(595);
        jQuery( '.colabs_container .group').add( '.colabs_container .group h2').hide();
        jQuery( '.colabs_container .group:first').show();
        jQuery( '.colabs_container #colabs-nav li').removeClass( 'current' );
        jQuery( '.colabs_container #colabs-nav li:first').addClass( 'current' );
        jQuery(this).text( '[+]' );
      }
    });
      jQuery( '.group').hide();
      jQuery( '.group:first').fadeIn();

            jQuery.fn.removeHidden = function () {
                jQuery(this).parents('.collapsed').nextAll().each( 
                function(){
                    if (jQuery(this).hasClass( 'last')) {
                        jQuery(this).removeClass( 'hidden' );
                        return false;
                    }
            jQuery(this).removeClass( 'hidden' );
                });
            }

            jQuery.fn.addHidden = function () {
                jQuery(this).parents('.collapsed').nextAll().each( 
                function(){
                    if (jQuery(this).hasClass( 'last')) {
                        jQuery(this).addClass( 'hidden' );
                        return false;
              }
                jQuery(this).addClass( 'hidden' );
                });                    
            }
            
            //CHECKBOX Element
      jQuery( '.group .collapsed').each(function(){
                jQuery(this).find( 'input:checked').removeHidden();
          });
            
      jQuery( '.group .collapsed input:checkbox').click(
      function (){
        if (jQuery(this).attr( 'checked')) {
          jQuery(this).removeHidden();
        } else {
                    jQuery(this).addHidden();
        }
      });
      
            //SELECT Element
      jQuery( '.group .collapsed select').each(function(){
                if ( '' == jQuery(this).val() ){
                    jQuery(this).removeHidden();
                }
          });

            jQuery( '.group .collapsed select').change(function(){
                if ( '' == jQuery(this).val() ){
                        jQuery(this).removeHidden();
                    }else{
                        jQuery(this).addHidden();
                    }
            });
            
            //RADIOBUTTON Element
      jQuery( '.group .collapsed').each(function(){
                jQuery(this).find( 'input:radio').removeHidden();
          });
                               
            jQuery(".group .collapsed input:radio").click(function(){
                if ( 'c' == jQuery(this).attr('value') ){
                    jQuery(this).parent().parent().parent().nextAll().removeClass( 'hidden' );
                } else {
          jQuery(this).parent().parent().parent().nextAll().each( 
            function(){
                  if (jQuery(this).filter( '.last').length) {
                    jQuery(this).addClass( 'hidden' );
                return false;
                  }
                  jQuery(this).addClass( 'hidden' );
                });                        
                }
            });
            
      jQuery( '.colabs-radio-img-img').click(function(){
        jQuery(this).parent().parent().find( '.colabs-radio-img-img').removeClass( 'colabs-radio-img-selected' );
        jQuery(this).addClass( 'colabs-radio-img-selected' );
      });
      jQuery( '.colabs-radio-img-label').hide();
      jQuery( '.colabs-radio-img-img').show();
      jQuery( '.colabs-radio-img-radio').hide();
      jQuery( '#colabs-nav li:first').addClass( 'current' );
      jQuery( '#colabs-nav li a').click(function(evt){
          jQuery( '#colabs-nav li').removeClass( 'current' );
          jQuery(this).parent().addClass( 'current' );
          var clicked_group = jQuery(this).attr( 'href' );
          jQuery( '.group').hide();
            jQuery(clicked_group).fadeIn();
          evt.preventDefault();
        });
      jQuery( 'select.colabs-typography-unit').change(function(){
        var val = jQuery(this).val();
        var parent = jQuery(this).parent();
        var name = parent.find( '.colabs-typography-size-px').attr( 'name' );
        if('' == name){ var name = parent.find( '.colabs-typography-size-em').attr( 'name' ); } 
        if('px' == val){ 
          parent.find( '.colabs-typography-size-em').hide().removeAttr( 'name' );
          parent.find( '.colabs-typography-size-px').show().attr( 'name',name);
        }
        else if('em' == val){
          parent.find( '.colabs-typography-size-em').show().attr( 'name',name);
          parent.find( '.colabs-typography-size-px').hide().removeAttr( 'name' );
        }
      });
      // Create sanitary variable for use in the JavaScript conditional.
      <?php
      $is_reset = 'false';
      if( isset( $_REQUEST['reset'] ) ) {
        $is_reset = $_REQUEST['reset'];
        $is_reset = strtolower( strip_tags( trim( $is_reset ) ) );
      } else {
        $is_reset = 'false';
      } // End IF Statement
      ?>
      if( '<?php echo $is_reset; ?>' == 'true'){
        var reset_popup = jQuery( '#colabs-popup-reset' );
        reset_popup.fadeIn();
        window.setTimeout(function(){
             reset_popup.fadeOut();                        
          }, 2000);
      }
    //Update Message popup
    jQuery.fn.center = function () {
      this.animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"},100);
      this.css( "left", 250 );
      return this;
    }
    jQuery(window).scroll(function() { 
      jQuery( '#colabs-popup-save').center();
      jQuery( '#colabs-popup-reset').center();
    });
    //String Builder Details
    jQuery( '.string_builder_return').each(function(){
      var top_object = jQuery(this);
      if(jQuery(this).children( '.string_option').length == 0){
        top_object.find( '.string_builder_empty').show();
      }
    })
    //AJAX Upload
    jQuery( '.image_upload_button').each(function(){
    var clickedObject = jQuery(this);
    var clickedID = jQuery(this).attr( 'id' );  
    new AjaxUpload(clickedID, {
        action: '<?php echo admin_url( "admin-ajax.php" ); ?>',
        name: clickedID, // File upload name
        data: { // Additional data to send
          action: 'colabs_ajax_post_action',
          type: 'upload',
          data: clickedID },
        autoSubmit: true, // Submit file after selection
        responseType: false,
        onChange: function(file, extension){},
        onSubmit: function(file, extension){
          clickedObject.text( 'Uploading' ); // change button text, when user selects file  
          this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
          interval = window.setInterval(function(){
            var text = clickedObject.text();
            if (text.length < 13){  clickedObject.text(text + '.' ); }
            else { clickedObject.text( 'Uploading' ); } 
          }, 200);
        },
        onComplete: function(file, response) {
        window.clearInterval(interval);
        clickedObject.text( 'Upload Image' ); 
        this.enable(); // enable upload button
        // If there was an error
        if(response.search( 'Upload Error') > -1){
          var buildReturn = '<span class="upload-error">' + response + '</span>';
          jQuery( ".upload-error").remove();
          clickedObject.parent().after(buildReturn);
        }
        else{
          var buildReturn = '<img class="hide colabs-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';
          jQuery( ".upload-error").remove();
          jQuery( "#image_" + clickedID).remove();  
          clickedObject.parent().after(buildReturn);
          jQuery( 'img#image_'+clickedID).fadeIn();
          clickedObject.next( 'span').fadeIn();
          clickedObject.parent().prev( 'input').val(response);
        }
        }
      });
    });
    //AJAX Remove (clear option value)
    jQuery( '.image_reset_button').click(function(){
        var clickedObject = jQuery(this);
        var clickedID = jQuery(this).attr( 'id' );
        var theID = jQuery(this).attr( 'title' ); 
        var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
        var data = {
          action: 'colabs_ajax_post_action',
          type: 'image_reset',
          data: theID
        };
        jQuery.post(ajax_url, data, function(response) {
          var image_to_remove = jQuery( '#image_' + theID);
          var button_to_hide = jQuery( '#reset_' + theID);
          image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
          button_to_hide.fadeOut();
          clickedObject.parent().prev( 'input').val( '' );
        });
        return false; 
      });
    //Adding string builder add
    jQuery( '.string_builder_add').click(function(){
        <?php // Nonce Security ?>
        <?php if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement ?>
        var clickedObject = jQuery(this);
        var id = jQuery(this).attr( 'id' );
        var name = jQuery( '#'+id+'_name').val();
        var value = jQuery( '#'+id+'_value').val();
        if('' == name || '' == value){ alert( 'Please add a value to one of the fields.' ); return false;}
        var data = 'id='+id+'&name=' + name + '&value=' + value;
        var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
        var data = {
          action: 'colabs_ajax_post_action',
          type: 'string_builder_add',
          data: data, 
          _ajax_nonce: '<?php echo $colabs_nonce; ?>'
        };
        jQuery.post(ajax_url, data, function(response) {
          var response = response.split( '|' );
          var id = response[0];
          var name = response[1]
          var value = response[2];
          var html = '';
          html += '<div class="string_option" id="'+name+'"><span>'+name+':</span> '+value+'</div>';
          jQuery( '#'+id+'_return').find( '.string_builder_empty').hide();
          jQuery( '#'+id+'_return').append(html);
        });
        return false; 
      });
          //AJAX Remove (clear option value)
    jQuery( '.string_option .delete').click(function(){
        <?php // Nonce Security ?>
        <?php if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement ?>
        var id = jQuery(this).parent().parent().parent().attr( 'id' );
        var name = jQuery(this).attr( 'rel' );
        var data = 'id='+id+'&name='+name;
        var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
        var data = {
          action: 'colabs_ajax_post_action',
          type: 'string_builder_delete',
          data: data, 
          _ajax_nonce: '<?php echo $colabs_nonce; ?>'
        };
        jQuery.post(ajax_url, data, function(response) {
          jQuery( '#string_builer_option_'+response).fadeOut( 'slow',function(){jQuery(this).remove();});
        });
        return false; 
      }); 
    //Save everything else
    jQuery( '#colabsform').submit(function(){
        function newValues() {
          var serializedValues = jQuery( "#colabsform *").not( '.colabs-ignore').serialize();
          return serializedValues;
        }
        jQuery( ":checkbox, :radio").click(newValues);
        jQuery( "select").change(newValues);
        jQuery( '.ajax-loading-img').fadeIn().css('display','inline');
        var serializedReturn = newValues();
        var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
        var data = {
          <?php if(isset($_REQUEST['page']) && 'colabsthemes' == $_REQUEST['page']){ ?>
          type: 'options',
          <?php } ?>
          <?php if(isset($_REQUEST['page']) && 'colabsthemes_framework_settings' == $_REQUEST['page']){ ?>
          type: 'framework',
          <?php } ?>
          <?php if(isset($_REQUEST['page']) && 'colabsthemes_layout_settings' == $_REQUEST['page']){ ?>
          type: 'layout',
          <?php } ?>                        
          <?php if(isset($_REQUEST['page']) && 'colabsthemes_seo' == $_REQUEST['page']){ ?>
          type: 'seo',
          <?php } ?>
          <?php if(isset($_REQUEST['page']) && 'colabsthemes_tumblog' == $_REQUEST['page']){ ?>
          type: 'tumblog',
          <?php } ?>
          action: 'colabs_ajax_post_action',
          data: serializedReturn, 
          <?php // Nonce Security ?>
          <?php if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement ?>
          _ajax_nonce: '<?php echo $colabs_nonce; ?>'
        };
        jQuery.post(ajax_url, data, function(response) {
          var success = jQuery( '#colabs-popup-save' );
          var loading = jQuery( '.ajax-loading-img' );
          loading.fadeOut();  
          success.fadeIn();
          window.setTimeout(function(){
             success.fadeOut(); 
          }, 2000);
        });
        return false; 
      });       
    });
  </script>
<?php }

/*-----------------------------------------------------------------------------------*/
/* Default Save Action - colabs_options_save */
/*-----------------------------------------------------------------------------------*/
/**
 * colabs_options_save()
 *
 * Save options to the database. Moved to a dedicated function.
 *
 * @since V4.6.0
 */
function colabs_options_save ( $type, $data ) {
global $wpdb; // this is how you get access to the database
  $status = false; // We set this to true if the settings have saved successfully.
  $save_type = $type;
  //Uploads
  if( 'upload' == $save_type ) {
    $clickedID = $data; // Acts as the name
    $filename = $_FILES[$clickedID];
        $filename['name'] = preg_replace( '/[^a-zA-Z0-9._\-]/', '', $filename['name']);
        //print_r($filename);
    $override['test_form'] = false;
    $override['action'] = 'wp_handle_upload';
    $uploaded_file = wp_handle_upload($filename,$override);
        $upload_tracking[] = $clickedID;
        update_option( $clickedID , $uploaded_file['url'] );
     if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }
     else { echo $uploaded_file['url']; } // Is the Response
  }
  elseif('image_reset' == $save_type){
      $id = $data; // Acts as the name
      delete_option($id);
  }
  elseif('string_builder_add' == $save_type){
    $data = $data;
    parse_str($data,$output);
    $id = $output['id'];
    $name = $output['name'];
    $name = preg_replace( '/[^a-zA-Z0-9-_ ]/i','',$name);
    $value = stripslashes($output['value']);
    $value = stripslashes($value);
    $return = "$id|$name|$value";
    echo $return;
    $option_temp = get_option($id);
    $option_temp[$name] = $value;
    update_option( $id, $option_temp );
  }
  elseif('string_builder_delete' == $save_type){
    $data = $data;
    parse_str($data,$output);
    $id = $output['id'];
    $name = $output['name'];
    echo str_replace( " ","_",$name);
    $option_temp = get_option($id);
    unset($option_temp[$name]);
    update_option($id,$option_temp);
  }
  elseif ('options' == $save_type OR 'seo' == $save_type OR 'tumblog' == $save_type OR 'framework' == $save_type OR 'layout' == $save_type) {
    // Make sure to flush the rewrite rules.
    colabs_flush_rewriterules();
    if ( is_array( $data ) ) {
      $output = $data; // $output variable used below during save.
    } else {
      parse_str( $data, $output );
    }
    // Remove the "colabs_save" item from the output array.
    if ( isset( $output['colabs_save'] ) && 'reset' == $output['colabs_save'] ) { unset( $output['colabs_save'] ); }
    //Pull options
          $options = get_option( 'colabs_template' );
    if('seo' == $save_type){
      $options = get_option( 'colabs_seo_template' ); } // Use SEO template on SEO page
    if('tumblog' == $save_type){
      $options = get_option( 'colabs_tumblog_template' ); } // Use Tumblog template on Tumblog page
    if('framework' == $save_type){
      $options = get_option( 'colabs_framework_template' ); } // Use Framework template on Framework Settings page
    if('layout' == $save_type){
      $options = get_option( 'colabs_layout_template' ); } // Use Layout template on Layout Settings page
    foreach($options as $option_array){
      if(isset($option_array['id'])){
        $id = $option_array['id'];
      } else { $id = null;}
      $old_value = get_option($id);
      $new_value = '';
            $multicheck_arr = array();
      if(isset($output[$id])){
        $new_value = $output[$option_array['id']];
      }
      if(isset($option_array['id'])) { // Non - Headings...
        //Import of prior saved options
        if('framework_colabs_import_options' == $id){
          //Decode and over write options.
          $new_import = $new_value;
          $new_import = unserialize($new_import);
          if(!empty($new_import)) {
            foreach($new_import as $id2 => $value2){
              if(is_serialized($value2)) {
                update_option($id2,unserialize($value2));
              } else {
                update_option($id2,$value2);
              }
            }
          }
        } else {
          $type = $option_array['type'];
          if ( is_array($type)){
            foreach($type as $array){
              if('text' == $array['type']){
                $id = $array['id'];
                $std = $array['std'];
                $new_value = $output[$id];
                if('' == $new_value){ $new_value = $std; }
                update_option( $id, stripslashes($new_value));
              }
            }
          }
          elseif ( 'text' == $type && 'seo' == $save_type ) { // Text Save
            $new_value = $output[$id];
            if( '' == $new_value && $std != '' ){ $new_value = $std; }
            $new_value = stripslashes( stripslashes( $new_value ) );
            update_option( $id, $new_value );
          }
          elseif('' == $new_value && 'checkbox' == $type){ // Checkbox Save
            update_option($id,'false' );
          }
          elseif ('true' == $new_value && 'checkbox' == $type){ // Checkbox Save
            update_option($id,'true' );
          }
                    elseif('multicheck' == $type || 'multicheck2' == $type){ // Multi Check Save
            $option_options = $option_array['options'];
            foreach ($option_options as $options_id => $options_value){
              $multicheck_id = $id . "_" . $options_id;
              if(!isset($output[$multicheck_id])){
                update_option($multicheck_id,'false' );
              }
              else{
                 update_option($multicheck_id,'true' );
                               $multicheck_arr[] = $options_id; //added
              }
            }
                        update_option($id,$multicheck_arr ); //added
          } 
          elseif('typography' == $type){
            $typography_array = array();
            $typography_array['size'] = $output[$option_array['id'] . '_size'];
            $typography_array['unit'] = $output[$option_array['id'] . '_unit'];
            $typography_array['face'] = stripslashes($output[$option_array['id'] . '_face']);
            $typography_array['style'] = $output[$option_array['id'] . '_style'];
            $typography_array['color'] = $output[$option_array['id'] . '_color'];
            update_option($id,$typography_array);
          }
          elseif('border' == $type){
            $border_array = array();
            $border_array['width'] = $output[$option_array['id'] . '_width'];
            $border_array['style'] = $output[$option_array['id'] . '_style'];
            $border_array['color'] = $output[$option_array['id'] . '_color'];
            update_option($id,$border_array);
          }
          elseif($type != 'upload_min'){
            update_option($id,stripslashes($new_value));
          }
        }
      }
    }
    // Assume that all has been completed and set $status to true.
    $status = true;
  }
  if( 'options' == $save_type OR 'framework' == $save_type OR 'layout' == $save_type ){
    /* Create, Encrypt and Update the Saved Settings */
    $colabs_options = array();
    $data = array();
    if('framework' == $save_type ){
      $options = get_option( 'colabs_template' );
    }
    $count = 0;
    foreach($options as $option){
      if(isset($option['id'])){
        $count++;
        $option_id = $option['id'];
        $option_type = $option['type'];
        if(is_array($option_type)) {
          $type_array_count = 0;
          foreach($option_type as $inner_option){
            $option_id = $inner_option['id'];
            if ( isset( $data[$option_id] ) )
              $data[$option_id] .= get_option($option_id);
            else
              $data[$option_id] = get_option($option_id);
          }
        }
        else {
          $data[$option_id] = get_option($option_id);
        }
      }
    }
    $output = "<ul>";
    foreach ($data as $name => $value){
        if(is_serialized($value)) {
          $value = unserialize($value);
          $colabs_array_option = $value;
          $temp_options = '';
          foreach($value as $v){
            if(isset($v))
              $temp_options .= $v . ',';
          }
          $value = $temp_options;
          $colabs_array[$name] = $colabs_array_option;
        } else {
          $colabs_array[$name] = $value;
        }
        $output .= '<li><strong>' . $name . '</strong> - ' . $value . '</li>';
    }
    $output .= "</ul>";
    if('layout' == $save_type ){
            update_option( 'colabs_layout_options', $colabs_array );
    } else { update_option( 'colabs_options', $colabs_array ); }
    // Assume that all has been completed and set $status to true.
    $status = true;
  }
  return $status;
} // End colabs_options_save()
/*-----------------------------------------------------------------------------------*/
/* Non-AJAX Save Action - colabs_nonajax_callback()
/*
/* This action is hooked on load of the various screens.
/* The hook is done when the pages are registered.
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'colabs_nonajax_callback' ) ) {
  function colabs_nonajax_callback() {
    if ( isset( $_POST['_ajax_nonce'] ) && isset( $_POST['colabs_save'] ) && ( 'save' == $_POST['colabs_save'] ) ) {
      $nonce_key = 'colabsframework-theme-options-update';
      switch ( $_REQUEST['page'] ) {
        case 'colabsthemes_options':
          $type = 'options';
          $nonce_key = 'colabsframework-theme-options-update';
        break;
        case 'colabsthemes_layout_settings':
          $type = 'layout';
          $nonce_key = 'colabsframework-layout-options-update';
        break;
        case 'colabsthemes_framework_settings':
          $type = 'framework';
          $nonce_key = 'colabsframework-framework-options-update';
        break;
        case 'colabsthemes_seo':
          $type = 'seo';
          $nonce_key = 'colabsframework-seo-options-update';
        break;
        case 'colabsthemes_tumblog':
          $type = 'tumblog';
        break;
        default:
          $type = '';
      }
      // check security with nonce.
      if ( function_exists( 'check_admin_referer' ) ) { check_admin_referer( $nonce_key, '_ajax_nonce' ); } // End IF Statement
      // Remove non-options fields from the $_POST.
      $fields_to_remove = array( '_wpnonce', '_wp_http_referer', '_ajax_nonce', 'colabs_save' );
      $data = array();
      foreach ( $_POST as $k => $v ) {
        if ( in_array( $k, $fields_to_remove ) ) {} else {
          $data[$k] = $v;
        }
      }
      $status = colabs_options_save( $type, $data );
      if ( $status ) {
        add_action( 'admin_notices', 'colabs_admin_message_success', 0 );
      } else {
        add_action( 'admin_notices', 'colabs_admin_message_error', 0 );
      }
    } // End IF Statement
  } // End colabs_nonajax_callback()
}
/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action - colabs_ajax_callback */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_colabs_ajax_post_action', 'colabs_ajax_callback' );
if ( ! function_exists( 'colabs_ajax_callback' ) ) {
  function colabs_ajax_callback() {
    // check security with nonce.
    if ( function_exists( 'check_ajax_referer' ) ) { check_ajax_referer( 'colabsframework-theme-options-update', '_ajax_nonce' ); } // End IF Statement
    $data = maybe_unserialize( $_POST['data'] );
    colabs_options_save( $_POST['type'], $data );
    die();
  } // End colabs_ajax_callback()
}
/*-----------------------------------------------------------------------------------*/
/* Generates The Options - colabsthemes_machine */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_machine')) {
function colabsthemes_machine($options) {
    $counter = 0;
  $menu = '';
  $output = '';
  foreach ($options as $value) {
    $counter++;
    $val = '';
    //Start Heading
     if ( $value['type'] != "heading" )
     {
      $class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
      $output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
      $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
      $output .= '<div class="option">'."\n" . '<div class="controls">'."\n";
     } 
     //End Heading
    $select_value = '';                                   
    switch ( $value['type'] ) {
    case 'text':
      $val = $value['std'];
      $std = get_option($value['id']);
      if ( $std != "") { $val = $std; }
            $val = stripslashes( $val ); // Strip out unwanted slashes.
      $output .= '<input class="regular-text" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
    break;
    case 'select':
      $output .= '<div class="select_wrapper"><select class="colabs-input" name="'. $value['id'] .'" id="'. $value['id'] .'">'; 
      $select_value = stripslashes(get_option($value['id']));
      foreach ($value['options'] as $option) {
        $selected = '';
         if($select_value != '') {
           if ( $select_value == $option) { $selected = ' selected="selected"';} 
           } else {
           if ( isset($value['std']) )
             if ($value['std'] == $option) { $selected = ' selected="selected"'; }
         }
         $output .= '<option'. $selected .'>';
         $output .= $option;
         $output .= '</option>';
       } 
       $output .= '</select></div>';
    break;
    case 'select2':
            $output .= '<div class="select_wrapper"><select class="colabs-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';                        
      $select_value = stripslashes(get_option($value['id']));
      foreach ($value['options'] as $option => $name) {
        $selected = '';
         if($select_value != '') {
           if ( $select_value == $option) { $selected = ' selected="selected"';} 
           } else {
           if ( isset($value['std']) )
             if ($value['std'] == $option) { $selected = ' selected="selected"'; }
         }
         $output .= '<option'. $selected .' value="'.$option.'">';
         $output .= $name;
         $output .= '</option>';
       } 
       $output .= '</select></div>';
    break;
    case 'calendar':
      $val = $value['std'];
      $std = get_option($value['id']);
      if ( $std != "") { $val = $std; }
            $output .= '<input class="colabs-input-calendar" type="text" name="'.$value['id'].'" id="'.$value['id'].'" value="'.$val.'">';
    break;
    case 'time':
      $val = $value['std'];
      $std = get_option($value['id']);
      if ( $std != "") { $val = $std; }
      $output .= '<input class="colabs-input-time" name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
    break;
    case 'textarea':
      $cols = '8';
      $ta_value = '';
      if(isset($value['std'])) {
        $ta_value = $value['std']; 
        if(isset($value['options'])){
          $ta_options = $value['options'];
          if(isset($ta_options['cols'])){
          $cols = $ta_options['cols'];
          } else { $cols = '8'; }
        }
      }
        $std = get_option($value['id']);
        if( $std != "") { $ta_value = stripslashes( $std ); }
        $output .= '<textarea class="colabs-input" name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';
    break;
    case "radio":
       $select_value = get_option( $value['id']);
       foreach ($value['options'] as $key => $option) 
       { 
         $checked = '';
           if($select_value != '') {
            if ( $select_value == $key) { $checked = ' checked'; } 
           } else {
          if ($value['std'] == $key) { $checked = ' checked'; }
           }
        $output .= '<input class="colabs-input colabs-radio" type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
      }
    break;
    case "checkbox": 
       $std = $value['std'];  
       $saved_std = get_option($value['id']);
       $checked = '';
      if(!empty($saved_std)) {
        if('true' == $saved_std) {
        $checked = 'checked="checked"';
        }
        else{
           $checked = '';
        }
      }
      elseif( 'true' == $std) {
         $checked = 'checked="checked"';
      }
      else {
        $checked = '';
      }
      $output .= '<input type="checkbox" class="checkbox colabs-input" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';
    break;
    case "multicheck":
      $std =  $value['std'];         
      foreach ($value['options'] as $key => $option) {
      $colabs_key = $value['id'] . '_' . $key;
      $saved_std = get_option($colabs_key);
      if(!empty($saved_std)) 
      { 
          if('true' == $saved_std){
           $checked = 'checked="checked"';  
          } 
          else{
            $checked = '';     
          }    
      } 
      elseif( $std == $key) {
         $checked = 'checked="checked"';
      }
      else {
        $checked = '';                                                                                    }
      $output .= '<input type="checkbox" class="checkbox colabs-input" name="'. $colabs_key .'" id="'. $colabs_key .'" value="true" '. $checked .' /><label for="'. $colabs_key .'">'. $option .'</label><br />';
      }
    break;
    case "multicheck2":
            if(is_array($value['std'])){
                $std = $value['std'];
            }else{        
                $std =  explode( ',',$value['std']);
            }
      foreach ($value['options'] as $key => $option) {
      $colabs_key = $value['id'] . '_' . $key;
      $saved_std = get_option($colabs_key);
      if(!empty($saved_std)) 
      { 
          if('true' == $saved_std){
           $checked = 'checked="checked"';  
          } 
          else{
            $checked = '';     
          }    
      } 
      elseif( in_array($key,$std)) {
         $checked = 'checked="checked"';
      }
      else {
        $checked = '';                                                                                    }
      $output .= '<input type="checkbox" class="checkbox colabs-input" name="'. $colabs_key .'" id="'. $colabs_key .'" value="true" '. $checked .' /><label for="'. $colabs_key .'">'. $option .'</label><br />';
      }
    break;
    case "upload":
      if ( function_exists( 'colabsthemes_medialibrary_uploader' ) ) {
        $output .= colabsthemes_medialibrary_uploader( $value['id'], $value['std'], null ); // New AJAX Uploader using Media Library
      } else {
        $output .= colabsthemes_uploader_function($value['id'],$value['std'],null); // Original AJAX Uploader
      } // End IF Statement
    break;
    case "upload_min":
      if ( function_exists( 'colabsthemes_medialibrary_uploader' ) ) {
        $output .= colabsthemes_medialibrary_uploader( $value['id'], $value['std'], 'min' ); // New AJAX Uploader using Media Library
      } else {
        $output .= colabsthemes_uploader_function($value['id'],$value['std'],'min' ); // Original AJAX Uploader
      } // End IF Statement
    break;
    case "color":
      $val = $value['std'];
      $stored  = get_option( $value['id'] );
      if ( $stored != "") { $val = $stored; }
      $output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div></div></div>';
      $output .= '<input class="colabs-color" name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
    break;   
    case "typography":
      $default = $value['std'];
      $typography_stored = get_option($value['id']);
      /* Font Size */
      $val = $default['size'];
      if ( $typography_stored['size'] != "") { $val = $typography_stored['size']; }
      if ( 'px' == $typography_stored['unit']){ $show_px = ''; $show_em = ' style="display:none" '; $name_px = ' name="'. $value['id'].'_size" '; $name_em = ''; }
      else if ( 'em' == $typography_stored['unit']){ $show_em = ''; $show_px = 'style="display:none"'; $name_em = ' name="'. $value['id'].'_size" '; $name_px = ''; }
      else { $show_px = ''; $show_em = ' style="display:none" '; $name_px = ' name="'. $value['id'].'_size" '; $name_em = ''; }
      $output .= '<select class="colabs-typography colabs-typography-size colabs-typography-size-px"  id="'. $value['id'].'_size" '. $name_px . $show_px .'>';
        for ($i = 9; $i < 71; $i++){ 
          if($val == strval($i)){ $active = 'selected="selected"'; } else { $active = ''; }
          $output .= '<option value="'. $i .'" ' . $active . '>'. $i .'</option>'; }
      $output .= '</select>';
      $output .= '<select class="colabs-typography colabs-typography-size colabs-typography-size-em" id="'. $value['id'].'_size" '. $name_em . $show_em.'>';
        $em = 0.5;
        for ($i = 0; $i < 39; $i++){
          if ($i <= 24)     // up to 2.0em in 0.1 increments
            $em = $em + 0.1;
          elseif ($i >= 14 && $i <= 24)   // Above 2.0em to 3.0em in 0.2 increments
            $em = $em + 0.2;
          elseif ($i >= 24)   // Above 3.0em in 0.5 increments
            $em = $em + 0.5;
          if($val == strval($em)){ $active = 'selected="selected"'; } else { $active = ''; }
          $output .= '<option value="'. $em .'" ' . $active . '>'. $em .'</option>'; }
      $output .= '</select>';
      /* Font Unit */
      $val = $default['unit'];
      if ( $typography_stored['unit'] != "") { $val = $typography_stored['unit']; }
        $em = ''; $px = '';
      if('em' == $val){ $em = 'selected="selected"'; }
      if('px' == $val){ $px = 'selected="selected"'; }
      $output .= '<select class="colabs-typography colabs-typography-unit" name="'. $value['id'].'_unit" id="'. $value['id'].'_unit">';
      $output .= '<option value="px" '. $px .'">px</option>';
      $output .= '<option value="em" '. $em .'>em</option>';
      $output .= '</select>';
      /* Font Face */
      $val = $default['face'];
      if ( $typography_stored['face'] != "") 
        $val = $typography_stored['face']; 
      $font01 = ''; 
      $font02 = ''; 
      $font03 = ''; 
      $font04 = ''; 
      $font05 = ''; 
      $font06 = ''; 
      $font07 = ''; 
      $font08 = '';
      $font09 = ''; 
      $font10 = '';
      $font11 = '';
      $font12 = '';
      $font13 = '';
      $font14 = '';
      $font15 = '';
            $font16 = '';
      if (strpos($val, 'Arial, sans-serif') !== false){ $font01 = 'selected="selected"'; }
      if (strpos($val, 'Verdana, Geneva') !== false){ $font02 = 'selected="selected"'; }
      if (strpos($val, 'Trebuchet') !== false){ $font03 = 'selected="selected"'; }
      if (strpos($val, 'Georgia') !== false){ $font04 = 'selected="selected"'; }
      if (strpos($val, 'Times New Roman') !== false){ $font05 = 'selected="selected"'; }
      if (strpos($val, 'Tahoma, Geneva') !== false){ $font06 = 'selected="selected"'; }
      if (strpos($val, 'Palatino') !== false){ $font07 = 'selected="selected"'; }
      if (strpos($val, 'Helvetica') !== false){ $font08 = 'selected="selected"'; }
      if (strpos($val, 'Calibri') !== false){ $font09 = 'selected="selected"'; }
      if (strpos($val, 'Myriad') !== false){ $font10 = 'selected="selected"'; }
      if (strpos($val, 'Lucida') !== false){ $font11 = 'selected="selected"'; }
      if (strpos($val, 'Arial Black') !== false){ $font12 = 'selected="selected"'; }
      if (strpos($val, 'Gill') !== false){ $font13 = 'selected="selected"'; }
      if (strpos($val, 'Geneva, Tahoma') !== false){ $font14 = 'selected="selected"'; }
      if (strpos($val, 'Impact') !== false){ $font15 = 'selected="selected"'; }
            if (strpos($val, 'Courier') !== false){ $font16 = 'selected="selected"'; }
      $output .= '<select class="colabs-typography colabs-typography-face" name="'. $value['id'].'_face" id="'. $value['id'].'_face">';
      $output .= '<option value="Arial, sans-serif" '. $font01 .'>Arial</option>';
      $output .= '<option value="Verdana, Geneva, sans-serif" '. $font02 .'>Verdana</option>';
      $output .= '<option value="&quot;Trebuchet MS&quot;, Tahoma, sans-serif"'. $font03 .'>Trebuchet</option>';
      $output .= '<option value="Georgia, serif" '. $font04 .'>Georgia</option>';
      $output .= '<option value="&quot;Times New Roman&quot;, serif"'. $font05 .'>Times New Roman</option>';
      $output .= '<option value="Tahoma, Geneva, Verdana, sans-serif"'. $font06 .'>Tahoma</option>';
      $output .= '<option value="Palatino, &quot;Palatino Linotype&quot;, serif"'. $font07 .'>Palatino</option>';
      $output .= '<option value="&quot;Helvetica Neue&quot;, Helvetica, sans-serif" '. $font08 .'>Helvetica*</option>';
      $output .= '<option value="Calibri, Candara, Segoe, Optima, sans-serif"'. $font09 .'>Calibri*</option>';
      $output .= '<option value="&quot;Myriad Pro&quot;, Myriad, sans-serif"'. $font10 .'>Myriad Pro*</option>';
      $output .= '<option value="&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, sans-serif"'. $font11 .'>Lucida</option>';
      $output .= '<option value="&quot;Arial Black&quot;, sans-serif" '. $font12 .'>Arial Black</option>';
      $output .= '<option value="&quot;Gill Sans&quot;, &quot;Gill Sans MT&quot;, Calibri, sans-serif" '. $font13 .'>Gill Sans*</option>';
      $output .= '<option value="Geneva, Tahoma, Verdana, sans-serif" '. $font14 .'>Geneva*</option>';
      $output .= '<option value="Impact, Charcoal, sans-serif" '. $font15 .'>Impact</option>';
            $output .= '<option value="Courier, &quot;Courier New&quot;, monospace" '. $font16 .'>Courier</option>';
      // Google webfonts      
      global $google_fonts;
      sort ($google_fonts);
      $output .= '<option value="">-- Google Fonts --</option>';
      foreach ( $google_fonts as $key => $gfont ) :
        $font[$key] = '';
        if ($val == $gfont['name']){ $font[$key] = 'selected="selected"'; }
        $name = $gfont['name'];
        $output .= '<option value="'.$name.'" '. $font[$key] .'>'.$name.'</option>';
      endforeach;     
      // Custom Font stack
      $new_stacks = get_option( 'framework_colabs_font_stack' );
      if(!empty($new_stacks)){
        $output .= '<option value="">-- Custom Font Stacks --</option>';
        foreach($new_stacks as $name => $stack){
          if (strpos($val, $stack) !== false){ $fontstack = 'selected="selected"'; } else { $fontstack = ''; }
          $output .= '<option value="'. stripslashes(htmlentities($stack)) .'" '.$fontstack.'>'. str_replace( '_',' ',$name).'</option>';
        }
      }
      $output .= '</select>';
      /* Font Weight */
      $val = $default['style'];
      if ( $typography_stored['style'] != "") { $val = $typography_stored['style']; }
        $normal = ''; $italic = ''; $bold = ''; $bolditalic = '';
      if('normal' == $val){ $normal = 'selected="selected"'; }
      if('italic' == $val){ $italic = 'selected="selected"'; }
      if('bold' == $val){ $bold = 'selected="selected"'; }
      if('bold italic' == $val){ $bolditalic = 'selected="selected"'; }
      $output .= '<select class="colabs-typography colabs-typography-style" name="'. $value['id'].'_style" id="'. $value['id'].'_style">';
      $output .= '<option value="normal" '. $normal .'>Normal</option>';
      $output .= '<option value="italic" '. $italic .'>Italic</option>';
      $output .= '<option value="bold" '. $bold .'>Bold</option>';
      $output .= '<option value="bold italic" '. $bolditalic .'>Bold/Italic</option>';
      $output .= '</select>';
      /* Font Color */
      $val = $default['color'];
      if ( $typography_stored['color'] != "") { $val = $typography_stored['color']; }     
      $output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div></div></div>';
      $output .= '<input class="colabs-color colabs-typography colabs-typography-color" name="'. $value['id'] .'_color" id="'. $value['id'] .'_color" type="text" value="'. $val .'" />';
    break;  
    case "border":
      $default = $value['std'];
      $border_stored = get_option( $value['id'] );
      /* Border Width */
      $val = $default['width'];
      if ( $border_stored['width'] != "") { $val = $border_stored['width']; }
      $output .= '<select class="colabs-border colabs-border-width" name="'. $value['id'].'_width" id="'. $value['id'].'_width">';
        for ($i = 0; $i < 21; $i++){ 
          if($val == $i){ $active = 'selected="selected"'; } else { $active = ''; }
          $output .= '<option value="'. $i .'" ' . $active . '>'. $i .'px</option>'; }
      $output .= '</select>';
      /* Border Style */
      $val = $default['style'];
      if ( $border_stored['style'] != "") { $val = $border_stored['style']; }
        $solid = ''; $dashed = ''; $dotted = '';
      if('solid' == $val){ $solid = 'selected="selected"'; }
      if('dashed' == $val){ $dashed = 'selected="selected"'; }
      if('dotted'== $val){ $dotted = 'selected="selected"'; }
      $output .= '<select class="colabs-border colabs-border-style" name="'. $value['id'].'_style" id="'. $value['id'].'_style">';
      $output .= '<option value="solid" '. $solid .'>Solid</option>';
      $output .= '<option value="dashed" '. $dashed .'>Dashed</option>';
      $output .= '<option value="dotted" '. $dotted .'>Dotted</option>';
      $output .= '</select>';
      /* Border Color */
      $val = $default['color'];
      if ( $border_stored['color'] != "") { $val = $border_stored['color']; }     
      $output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div></div></div>';
      $output .= '<input class="colabs-color colabs-border colabs-border-color" name="'. $value['id'] .'_color" id="'. $value['id'] .'_color" type="text" value="'. $val .'" />';
    break;   
    case "images":
      $i = 0;
      $select_value = get_option( $value['id']);
      foreach ($value['options'] as $key => $option) 
       { 
       $i++;
         $checked = '';
         $selected = '';
           if($select_value != '') {
            if ( $select_value == $key) { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; } 
            } else {
            if ($value['std'] == $key) { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; }
            elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; }
            elseif ($i == 1  && '' == $value['std']) { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; }
            else { $checked = ''; }
          } 
        $output .= '<span>';
        $output .= '<input type="radio" id="colabs-radio-img-' . $value['id'] . $i . '" class="checkbox colabs-radio-img-radio" value="'.$key.'" name="'. $value['id'].'" '.$checked.' />';
        $output .= '<div class="colabs-radio-img-label">'. $key .'</div>';
        $output .= '<img src="'.$option.'" alt="" class="colabs-radio-img-img '. $selected .'" onClick="document.getElementById(\'colabs-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
        $output .= '</span>';
      }
    break; 
    case "info":
      $default = $value['std'];
      $output .= $default;
    break; 
    case "string_builder":
      $desc = $value['std'];
      $output .= '<div id="'.$value['id'].'">';
      $output .= 'Name<input class="colabs-input colabs-ignore" name="name" id="'. $value['id'] .'_name" type="text" />';
      $output .= 'Font Stack<input class="colabs-input colabs-ignore" name="value" id="'. $value['id'] .'_value" type="text" />';
      $output .= '<div class="add_button"><a class="button string_builder_add" href="#" class="string_builder" id="'.$value['id'].'">Add</a></div>';
      $output .= '<div id="'.$value['id'].'_return" class="string_builder_return">';
      $output .= '<h3>'.$desc.'</h3>';
      $saved_data = get_option($value['id']);
      if(!empty($saved_data)){
        foreach($saved_data as $name => $data){
          $data = stripslashes($data);  
          $output .= '<div class="string_option" id="string_builer_option_'.str_replace( ' ','_',$name).'"><a class="delete" rel="'.$name.'" href="#"><img src="'.get_template_directory_uri().'/functions/images/ico-close.png" /></a><span>'.str_replace( '_',' ',$name) .':</span> '. $data .'</div>';
        }
      }
      $output .= '<div style="display:none" class="string_builder_empty">Nothing added yet.</div>';     
      $output .= '</div>';
      $output .= '</div>';
    break;                               
    case "heading":
      if($counter >= 2){
         $output .= '</div>'."\n";
      }
      $jquery_click_hook = ereg_replace( "[^A-Za-z0-9]", "", strtolower($value['name']) );
            $jquery_click_hook = str_replace(' ', '', $jquery_click_hook);
      $jquery_click_hook = "colabs-option-" . $jquery_click_hook;
            $class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
      $menu .= '<li class="'.$value['icon'].'">
        <a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">
          <img src="'.get_template_directory_uri().'/functions/images/icon/menu/'.$value['icon'].'-settings.png">
          <span>'.  $value['name'] .'</span></a>
      </li>';
      $output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
    break;                                  
    } 
    // if TYPE is an array, formatted into smaller inputs... ie smaller values
    if ( is_array($value['type'])) {
      foreach($value['type'] as $array){
          $id = $array['id']; 
          $std = $array['std'];
          $saved_std = get_option($id);
          if($saved_std != $std){$std = $saved_std;} 
          $meta = $array['meta'];
          if('text' == $array['type']) { // Only text at this point
             $output .= '<input class="input-text-small colabs-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';  
             $output .= '<span class="meta-two">'.$meta.'</span>';
          }
        }
    }
    if ( $value['type'] != "heading" ) { 
      if ( $value['type'] != "checkbox" ) 
        { 
        $output .= '<br/>';
        }
      if(!isset($value['desc'])){ $explain_value = ''; } else{ $explain_value = $value['desc']; } 
      $output .= '</div><span class="description"><label for="';
      if(isset($value['id'])) $output .= $value['id'];
      $output .= '">'. $explain_value .'</label></span>'."\n";
      $output .= '<div class="clear"> </div></div></div>'."\n";
      }
  }
    //Checks if is not the Content Builder page
    if ( isset($_REQUEST['page']) && $_REQUEST['page'] != 'colabsthemes_content_builder' ) {
    $output .= '</div>';
  }
    return array($output,$menu);
}
}
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Uploader - colabsthemes_uploader_function */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_uploader_function')) {
function colabsthemes_uploader_function($id,$std,$mod){
  $uploader = '';
    $upload = get_option($id);
  if($mod != 'min') { 
      $val = $std;
            if ( get_option( $id ) != "") { $val = get_option($id); }
            $uploader .= '<input class="colabs-input" name="'. $id .'" id="'. $id .'_upload" type="text" value="'. $val .'" />';
  }
  $uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">Upload Image</span>';
  if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
  $uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
  $uploader .='</div>' . "\n";
    $uploader .= '<div class="clear"></div>' . "\n";
  if(!empty($upload)){
      $uploader .= '<a class="colabs-uploaded-image" href="'. $upload . '">';
      $uploader .= '<img class="colabs-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
      $uploader .= '</a>';
    }
  $uploader .= '<div class="clear"></div>' . "\n"; 
return $uploader;
}
}
/*-----------------------------------------------------------------------------------*/
/* CoLabsthemes Theme Version Checker - colabsthemes_version_checker */
/* @local_version is the installed theme version number */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'colabsthemes_version_checker' ) ) {
  function colabsthemes_version_checker ( $local_version ) {
    function do_not_cache_feeds( &$feed ) {
      $feed->enable_cache(false);
    }
    add_action( 'wp_feed_options', 'do_not_cache_feeds' );
    add_filter( 'http_request_args', 'colabsthemes_http_request_args', 100, 1 );
    function colabsthemes_http_request_args( $r ) //called on line 237
    {
      $r['timeout'] = 15;
      return $r;
    }
    add_action( 'http_api_curl', 'colabsthemes_http_api_curl', 100, 1 );
    function colabsthemes_http_api_curl( $handle ) //called on line 1315
    {
      curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 15 );
      curl_setopt( $handle, CURLOPT_TIMEOUT, 15 );
    }
    // Get a SimplePie feed object from the specified feed source.
    $theme_name = str_replace( "-", "", strtolower( get_option( 'colabs_themename' ) ) );
    // Use a transient to store the current theme version data for 24 hours.
    $latest_version_via_rss = '';
    $version_data = get_transient( $theme_name . '_version_data' );
    if( $version_data ){
      $latest_version_via_rss = $version_data;
    }
    // If the transient has expired, run the check.
    if ( '' == $latest_version_via_rss ) {
      $feed_url = 'http://colorlabsproject.com/?feed=updates&theme=' . $theme_name;
      $rss = fetch_feed( $feed_url );
      // Of the RSS is failed somehow.
      if ( is_wp_error( $rss ) ) {
        // Return without notification
        return;
      }
      //Figure out how many total items there are, but limit it to 5.
      $maxitems = $rss->get_item_quantity( 5 );
      // Build an array of all the items, starting with element 0 (first element).
      $rss_items = $rss->get_items( 0, $maxitems );
      if ( $maxitems == 0 ) { $latest_version_via_rss = 0; }
        else {
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $rss_items as $item ) :       
          $latest_version_via_rss = $item->get_title();
          break; // Take only the first version number. Break away when we have it.
        endforeach;
      }
      // Set the transient containing the latest version number.
      set_transient( $theme_name . '_version_data', $latest_version_via_rss , 60*60*24 );
    } // End Version Check
    //Check if version is the latest - assume standard structure x.x.x
    $pieces_rss = explode( '.', $latest_version_via_rss );
    $pieces_local = explode( '.', $local_version );
    //account for null values in second position x.2.x
    if( isset( $pieces_rss[0] ) && $pieces_rss[0] != 0 ) {
      if ( ! isset( $pieces_rss[1] ) )
        $pieces_rss[1] = '0';
      if ( ! isset( $pieces_local[1] ) )
        $pieces_local[1] = '0';
      //account for null values in third position x.x.3
      if ( ! isset( $pieces_rss[2] ) )
        $pieces_rss[2] = '0';
      if ( ! isset( $pieces_local[2] ) )
        $pieces_local[2] = '0';
      //do the comparisons
      $version_sentinel = false;
      $status = 'bugfix';
      // Setup update statuses
      $statuses = array(
              'new_version' => __( 'New Version', 'colabsthemes' ), 
              'new_feature' => __( 'New Feature', 'colabsthemes' ), 
              'bugfix' => __( 'Bugfix', 'colabsthemes' )
              );
      // New version
      if ( $pieces_rss[0] > $pieces_local[0] ) {
        $version_sentinel = true;
        $status = 'new_version';
      }
      // New feature
      if ( ( $pieces_rss[1] > $pieces_local[1] ) && ( $version_sentinel == false ) && ( $pieces_rss[0] == $pieces_local[0] ) ) {
        $version_sentinel = true;
        $status = 'new_feature';
      }
      // Bugfix
      if ( ( $pieces_rss[2] > $pieces_local[2] ) && ( $version_sentinel == false ) && ( $pieces_rss[0] == $pieces_local[0] ) && ( $pieces_rss[1] == $pieces_local[1] ) ) {
        $version_sentinel = true;
        $status = 'bugfix';
      }
      return array( 'is_update' => $version_sentinel, 'version' => $latest_version_via_rss, 'status' => $statuses[$status], 'theme_name' => $theme_name );
      //set version checker message
      if ( $version_sentinel == true ) {
        $update_message = '<div class="update_available status-' . $status . '">' . __( 'Theme update is available', 'colabsthemes' ) . ' (v.' . $latest_version_via_rss . ') - <a href="http://colorlabsproject.com/themes/">' . __( 'Get the new version', 'colabsthemes' ) . '</a>.<p>' . sprintf( __( 'Update Type: %s', 'colabsthemes' ), $statuses[$status] ) . '</p></div>';
      }
      else {
        $update_message = '';
      }
    } else {
        $update_message = '';
    }
    return $update_message;
  }
} // End colabsthemes_version_checker()
/*-----------------------------------------------------------------------------------*/
/* CoLabsthemes Thumb Detection Notice - colabs_thumb_admin_notice */
/*-----------------------------------------------------------------------------------*/
function colabs_thumb_admin_notice(){
    global $current_user;
    $current_user_id = $current_user->user_login;
    $super_user = get_option( 'framework_colabs_super_user' );
    if( $super_user == $current_user_id || empty( $super_user ) ) {
      $timthumb_update = get_option('colabs_timthumb_update');
      if ( isset( $_GET['page'] ) ) { $page = $_GET['page']; } else { $page = ''; }
      $url = admin_url( 'admin.php?page=colabsthemes_timthumb_update' );
      if ( ( locate_template( 'thumb.php' ) != '' ) && '' == $timthumb_update && $page != 'colabsthemes_timthumb_update' ) {
        echo '<div class="error">
             <p>Old version of TimThumb detected in your theme folder. <a href="'.$url.'">Click here to update</a>.</p>
          </div>';     
      } // End If Statement
    } // End If Statement
}
update_option( 'colabs_timthumb_update', '' );
add_action( 'admin_notices', 'colabs_thumb_admin_notice' );
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Theme Update Admin Notice - colabs_theme_update_notice */
/*-----------------------------------------------------------------------------------*/
global $pagenow;
if ( 'admin.php' == $pagenow && isset( $_GET['page'] ) && 'colabsthemes' == $_GET['page'] && 'true' == get_option( 'framework_colabs_theme_version_checker' ) ) {
  add_action( 'admin_notices', 'colabs_theme_update_notice', 10 );
} // End IF Statement
if ( ! function_exists( 'colabs_theme_update_notice' ) ) {
  function colabs_theme_update_notice () {
  
    $local_version = COLABS_THEME_VER;
  
    $update_data = colabsthemes_version_checker( $local_version );
      $html = '';
      if ( is_array( $update_data ) && $update_data['is_update'] == true ) {
        $html = '<div id="theme_update" class="updated fade"><p>' . sprintf( __( 'Theme update is available (v%s). %sDownload new version%s (%sSee Changelog%s)', 'colabsthemes' ), $update_data['version'], '<a href="http://colorlabsproject.com/themes/">', '</a>', '<a href="http://colorlabsproject.com/updates/' . $update_data['theme_name'] . '/changelog.txt" target="_blank" title="Changelog">', '</a>' ) . '</p></div>';
      }
      if ( $html != '' ) { echo $html; }
  } // End colabs_theme_update_notice()
} // End IF Statement
?>