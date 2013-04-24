<?php

/**
 * Outputs the ColorLabs Custom File Editor
 *
 * @package Backbone
 * @since 1.0
 */ 
class colabs_custom_editor {
    function get_custom_files() {
        $files = array();
        $directory = opendir(COLABS_CUSTOM); // Open the directory
        $exts = array('.php', '.css', '.js', '.txt', '.inc', '.htaccess', '.html', '.htm'); // What type of files do we want?

        while ($file = readdir($directory)) { // Read the files
            if ($file != '.' && $file != '..' && (strpos($file, 'layout') === false)) { // Only list files within the _current_ directory
                $extension = substr($file, strrpos($file, '.')); // Get the extension of the file

                if ($extension && in_array($extension, $exts)) // Verify extension of the file; we can't edit images!
                    $files[] = $file; // Add the file to the array
            }
        }

        closedir($directory); // Close the directory
        return $files; // Return the array of editable files
    }

    function is_custom_writable($file, $files) {
        if (!in_array($file, $files))
            $error = "<p><strong>" . __('Attention!', 'colabsthemes') . '</strong> ' . __('For security reasons, the file you are attempting to edit cannot be modified via this screen.', 'colabsthemes') . '</p>';
        elseif (!file_exists(COLABS_CUSTOM)) // The custom/ directory does not exist
            $error = "<p><strong>" . __('Attention!', 'colabsthemes') . '</strong> ' . __('Your <code>custom/</code> directory does not appear to exist. Have you remembered to rename <code>/custom-sample</code>?', 'colabsthemes') . '</p>';
        elseif (!is_file(COLABS_CUSTOM . '/' . $file)) // The selected file does not exist
            $error = "<p><strong>" . __('Attention!', 'colabsthemes') . '</strong> ' . __('The file you are attempting does not appear to exist.', 'colabsthemes') . '</p>';
        elseif (!is_writable(COLABS_CUSTOM . '/custom.css')) // The selected file is not writable
            $error = "<p><strong>" . __('Attention!', 'colabsthemes') . '</strong> ' . sprintf(__('Your <code>/custom/%s</code> file is not writable by the server, and in order to modify the file via the admin panel, Backbone needs to be able to write to this file. All you have to do is set this file&#8217;s permissions to 666, and you&#8217;ll be good to go.', 'colabsthemes'), $file) . '</p>';
        elseif( isset($_GET['updated']) && $_GET['updated'] == 'false') // Update Failed
            $error = "<p><strong>" . __('Attention!', 'colabsthemes') . '</strong> ' . __('Wrong file type, please upload css/txt file to add custom css. Thank you.', 'colabsthemes') . '</p>';
        
        if ( isset($error) ) { // Return the error + markup, if required
            $error = "<div class=\"warning\">\n\t$error\n</div>\n";
            return $error;
        }

        return false;
    }

    function save_file() {
        if (!current_user_can('edit_theme_options'))
            wp_die(__('You don&#8217;t have admin privileges to access theme options.', 'colabsthemes'));

        $custom_editor = new colabs_custom_editor;
        $allowed_files = $custom_editor->get_custom_files(); // Get list of allowed files
        
        if (isset($_POST['custom_file_submit'])) {
            check_admin_referer('colabs-custom-file', '_wpnonce-colabs-custom-file');
            $contents = stripslashes($_POST['newcontent']); // Get new custom content
            $file = $_POST['file']; // Which file?

            if (!in_array($file, $allowed_files)) // Is the file allowed? If not, get outta here!
                wp_die(__('You have attempted to modify an ineligible file. Only files within the ColorLabs <code>/custom</code> folder may be modified via this interface. Thank you.', 'colabsthemes'));

            $wp_filesystem = WP_Filesystem($cred);
            global $wp_filesystem;
            if ( $wp_filesystem->put_contents( COLABS_CUSTOM . '/' . $file , $contents, FS_CHMOD_FILE) ){
            $updated = '&updated=true'; // Display updated message
            }
        }
        elseif (isset($_POST['custom_file_jump'])) {
            check_admin_referer('colabs-custom-file-jump', '_wpnonce-colabs-custom-file-jump');
            $file = $_POST['custom_files'];
            $updated = '';
        }
        elseif( isset($_POST['custom_file_import']) ){
            check_admin_referer('colabs-custom-file', '_wpnonce-colabs-custom-file');

            if($_FILES['import']['name']!=''){
            $wp_filesystem = WP_Filesystem($cred);
            global $wp_filesystem;
            
            $uploadcssfile = $wp_filesystem->get_contents( $_FILES['import']['tmp_name'] );     
            $uploadcssfile = htmlspecialchars($uploadcssfile);

            $allow_ext = array('.css');
            $extension = substr($_FILES['import']['name'], strrpos($_FILES['import']['name'], '.'));
            
                if (!in_array($extension, $allow_ext)){ // Is the file allowed? If not, get outta here!
                    $updated = '&updated=false';
                }else{
                    $file = 'custom.css'; // Which file?
                    
                    $content = $wp_filesystem->get_contents( COLABS_CUSTOM . '/' . $file );
                    $content = htmlspecialchars($content);                    
    
                    if (!in_array($file, $allowed_files)) // Is the file allowed? If not, get outta here!
                        wp_die(__('YYou have attempted to modify an ineligible file. Only files within the ColorLabs <code>/custom</code> folder may be modified via this interface. Thank you.', 'colabsthemes'));
                    
                    //Append uploaded css file into custom.css
                    $content.= "\n\n".$uploadcssfile;
                    
                    if ( $wp_filesystem->put_contents( COLABS_CUSTOM . '/' . $file , $content, FS_CHMOD_FILE) ){
                    $updated = '&updated=true'; // Display updated message
                    }
                }
            }else{ $updated = '&updated=false'; }
        }
        wp_redirect(admin_url("admin.php?page=colabsthemes_editor$updated&file=$file"));
        
    } //END OF save_file

    function options_page() {
        $themename =  get_option( 'colabs_themename' );
        $custom_editor = new colabs_custom_editor;
?>

<div id="colabs_options" class="wrap<?php if (is_rtl()) { echo ' rtl'; } ?>">
<?php
    if (file_exists(COLABS_CUSTOM)) {
        // Determine which file we're editing. Default to something harmless, like custom.css.
        if(isset($_GET['file'])){ $file = $_GET['file']; }else{ $file = 'custom.css'; }
        $files = $custom_editor->get_custom_files();
        $extension = substr($file, strrpos($file, '.'));

        // Determine if the custom file exists and is writable. Otherwise, this page is useless.
        $error = $custom_editor->is_custom_writable($file, $files);
        
        if (!$error){
            // Get contents of custom.css
            if (filesize(COLABS_CUSTOM . '/' . $file) > 0) {
                $wp_filesystem = WP_Filesystem($cred);
                global $wp_filesystem;
                $content = $wp_filesystem->get_contents( COLABS_CUSTOM . '/' . $file );
                $content = htmlspecialchars($content);
            }
            else{ $content = ''; }
        }
        
?>
    <div class="one_col wrap colabs_container">
    
        <div class="clear"></div>
                <?php colabs_theme_check();?>
        <div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save">File Updated</div></div>
        <div style="width:100%;padding-top:15px;"></div>
        <div class="clear"></div>
        
    <div id="main">
        
    <div id="panel-header">
        <?php colabsthemes_options_page_header('save_button=false'); ?>
    </div><!-- #panel-header -->

    <div id="panel-content">
    
    <?php if($error) echo $error; ?>
    
    <?php do_action('colabsthemes_editor_content'); ?>
    
    <div class="section">
        <h3 class="heading">Upload Custom CSS File</h3>
        <div class="option">
            <form method="post" enctype="multipart/form-data" id="colabsform" action="<?php echo admin_url('admin-post.php?action=colabs_file_editor'); ?>">
                <input id="css_file" type="file" name="import"/>
                <?php wp_nonce_field('colabs-custom-file', '_wpnonce-colabs-custom-file'); ?>
                <input type="submit" class="button" value="Upload File" name="custom_file_import" />
            </form>
        </div><!-- .option -->
    </div><!-- .section -->    
    
    <div class="section">
    <h3 class="heading">File Editor</h3>
    <div class="option">
        <form style="overflow:hidden" method="post" id="file-jump" name="file-jump" action="<?php echo admin_url('admin-post.php?action=colabs_file_editor'); ?>">
            <h3><?php printf(__('Currently editing: <code>%s</code>', 'colabsthemes'), "custom/$file"); ?></h3>
            <p>
                <select id="custom_files" name="custom_files">
                    <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                    <?php
                            foreach ($files as $f) // An option for each available file
                                if ($f != $file) echo "\t\t\t\t\t<option value=\"$f\">$f</option>\n";
                    ?>
                </select>
                <?php wp_nonce_field('colabs-custom-file-jump', '_wpnonce-colabs-custom-file-jump'); ?>
                <input type="submit" id="custom_file_jump" name="custom_file_jump" value="<?php _e('Edit selected file', 'colabsthemes'); ?>" />
            </p>
        
        <?php
                if ('.php' == $extension)
                    echo "\t\t\t<p class=\"alert\">" . __('<strong>Note:</strong> If you make a mistake in your code while modifying a <acronym title="PHP: Hypertext Preprocessor">PHP</acronym> file, saving this page <em>may</em> result your site becoming temporarily unusable. Prior to editing such files, be sure to have access to the file via <acronym title="File Transfer Protocol">FTP</acronym> or other means so that you can correct the error.', 'colabsthemes') . "</p>\n";
        ?>
        </form>
        
        <form class="file_editor" method="post" id="template" name="template" action="<?php echo admin_url('admin-post.php?action=colabs_file_editor'); ?>">
            <div class="save_bar_top right">
                <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-top left" alt="Working..." />
                <?php wp_nonce_field('colabs-custom-file', '_wpnonce-colabs-custom-file'); ?>
                <input type="submit" value="Save All Changes" class="button submit-button button-primary" name="custom_file_submit"/>
            </div>        
            <input type="hidden" id="file" name="file" value="<?php echo $file; ?>" />
            <p><textarea id="newcontent" name="newcontent" rows="25" cols="50" class="large-text"><?php echo $content; ?></textarea></p>
            <span>
                <?php wp_nonce_field('colabs-custom-file', '_wpnonce-colabs-custom-file'); ?>
          <input type="submit" class="button submit-button button-primary save_button" id="custom_file_submit" value="Save All Changes" name="custom_file_submit" />
          
          <span id="colabs_editor_color_picker" class="colorSelector"></span>
          <input class="colabs-color input-text-small" name="colabs_editor_color" id="colabs_editor_color" type="text" value="#ffffff" />
          <label class="inline" for="colabs_editor_color" style="display: inline-block; margin-top: -18px;"><?php _e('Color Reference', 'colabsthemes'); ?></label>
            </span>
        </form>
    </div><!-- .option -->
    </div><!-- .section -->
        
    </div><!--/#panel-content -->

    <div id="panel-footer">
        <ul>
            <li class="docs"><a title="Theme Documentation" href="http://colorlabsproject.com/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" >View Documentation</a></li>
            <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank">Submit a Support Ticket</a></li>
            <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank">Suggest a Feature</a></li>
        </ul>
    </div><!--/#panel-footer -->
    
    </div><!--/#main -->

    </div><!--/.colabs_container -->
    
<?php
    }
    else
        echo "<div class=\"warning\">\n\t<p><strong>" . __('Attention!', 'colabsthemes') . '</strong> ' . __('In order to edit your custom files, you&#8217;ll need to change the name of your <code>custom-sample</code> folder to <code>custom</code>.', 'colabsthemes') . "</p>\n</div>\n";
?>
</div>
<?php
    }
} //END of colabs_custom_editor CLASS

//Editor Options
if (!function_exists('colabs_options_editor')) {
function colabs_options_editor(){
    
    $themename =  get_option( 'colabs_themename' );
    $manualurl =  get_option( 'colabs_manual' );
    $shortname =  'colabs_editor';

    //More Options
    $editor_options = array();
    
    /* General Styling */
    $editor_options[] = array( "name" =>  __( 'Color Reference', 'colabsthemes' ),
                        "desc" => __( 'Color reference for custom file editor', 'colabsthemes' ),
                        "id" => $shortname."_color",
                        "std" => "fff",
                        "type" => "color");

if ( get_option('colabs_editor_template') != $editor_options) update_option('colabs_editor_template',$editor_options);      
    

} // END colabs_options_editor()
} // END function_exists()

add_action('admin_head','colabs_options_editor');
add_action('admin_post_colabs_file_editor', array('colabs_custom_editor', 'save_file'));

//Editor Load Scripts
if (!function_exists('colabs_load_only_editor')) {
function colabs_load_only_editor(){
    add_action( 'admin_head', 'colabs_admin_head_editor' );     

    function colabs_admin_init(){
        
    }//END function colabs_admin_init

    function colabs_admin_head_editor(){
        
        $wp_version = get_bloginfo( 'version' );
        
        wp_enqueue_style( 'colabs-admin-style', get_template_directory_uri() . '/functions/admin-style.css' );
        
        if (version_compare($wp_version, '3.4.0', '<')) {
            wp_enqueue_style( 'colabs-admin-colorpicker', get_template_directory_uri() . '/functions/css/colorpicker.css', array( 'colabs-admin-style' ) );
            wp_enqueue_script( 'colabs-admin-colorpicker-js', get_template_directory_uri() . '/functions/js/colorpicker.js' );
        }else{
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');
        }
        
        wp_enqueue_style( 'colabs-admin-codemirror', get_template_directory_uri() . '/functions/css/codemirror.css', array( 'colabs-admin-style' ) );
        wp_enqueue_script( 'colabs-admin-codemirror-js', get_template_directory_uri() . '/functions/js/codemirror.js' );
                        
?>
        <style type="text/css">
            .activeline {background: #e8f2ff !important;}
            .colabs_container #template{ position: relative }
            .colabs_container #template div{ margin-right: 0 !important }
            .colabs_container input.submit-button{ float: left; margin-right: 20px }
            .colabs_container .colorSelector{ margin-right: 5px }
            .colabs_container .colorSelector:hover{ cursor: pointer }
            .colabs_container input.colabs-color{ width: 100px }
            .colabs_container .save_bar_top{ position: absolute; right: 0; top: -35px }
            .colabs_container p.alert{ margin-right: 150px }
        </style>

        <script type="text/javascript" language="javascript">
        jQuery(document).ready(function(){
        
        <?php if (isset($_GET['updated'])){ if ( 'true' == $_GET['updated'] ) { ?>
            var success = jQuery( '#colabs-popup-save' );
            var loading = jQuery( '.ajax-loading-img' );
            loading.fadeOut();  
            success.fadeIn();
            window.setTimeout(function(){
               success.fadeOut(); 
            }, 4000);
        <?php }} ?>

      // Codemirror - sytax highlighting
      // Initiate codemirror for syntax highlighting
      var cssEditor = CodeMirror.fromTextArea(document.getElementById("newcontent"), {
        mode: 'text/css',
        lineWrapping: true,
        styleActiveLine: true,
        onChange: function() {
          $placeholder.html( cssEditor.getValue() );
          $editor.html( cssEditor.getValue() );
        }
      });
      // var hlLine = cssEditor.setLineClass(0, "activeline");

            //Color Picker
            var bgImage = jQuery("#custom-background-image"),
                frame;
   
            <?php 
            $options = get_option( 'colabs_editor_template' );
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
                    bgImage.css('<?php echo $option_id; ?>-color', ui.color.toString());
                },
                clear: function() {
                    bgImage.css('<?php echo $option_id; ?>-color', '');
                }
            });
            
            <?php }else{ ?>
            
                 jQuery( '#<?php echo $option_id; ?>_picker').children( 'span').css( 'backgroundColor', '<?php echo esc_js( $color ); ?>' );    
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
                        jQuery( '#<?php echo $option_id; ?>_picker').children( 'span').css( 'backgroundColor', '#' + hex);
                        jQuery( '#<?php echo $option_id; ?>_picker').next( 'input').attr( 'value','#' + hex);
                    }
                  });
              <?php } 
              
              } } ?>
              
        });
        </script> 
<?php
    }//END function colabs_admin_head_editor
}//END function colabs_load_only_editor
}//END function_exists
?>