<?php 
// Get the path to the root.
$full_path = __FILE__;

$path_bits = explode( 'wp-content', $full_path );

$url = $path_bits[0];

// Require WordPress bootstrap.
require_once( $url . '/wp-load.php' );
                                   
$colabs_framework_version = get_option( 'colabs_framework_version' );

$MIN_VERSION = '1.0';

$meetsMinVersion = version_compare($colabs_framework_version, $MIN_VERSION) >= 0;

$colabs_framework_path = dirname(__FILE__) .  '/../../';

$colabs_framework_url = get_template_directory_uri() . '/functions/';

$colabs_shortcode_css = $colabs_framework_path . 'css/shortcodes.css';
                                  
$isCoLabsTheme = file_exists($colabs_shortcode_css);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<div id="colabs-dialog">

<?php if ( $meetsMinVersion && $isCoLabsTheme ) { ?>

<div id="colabs-options-buttons" class="clear">
	<div class="alignleft">
	
	    <input type="button" id="colabs-btn-cancel" class="button" name="cancel" value="Cancel" accesskey="C" />
	    
	</div>
	<div class="alignright">
	
	    <input type="button" id="colabs-btn-preview" class="button" name="preview" value="Preview" accesskey="P" />
	    <input type="button" id="colabs-btn-insert" class="button-primary" name="insert" value="Insert" accesskey="I" />
	    
	</div>
	<div class="clear"></div><!--/.clear-->
</div><!--/#colabs-options-buttons .clear-->

<div id="colabs-options" class="alignleft">
    <h3><?php echo __( 'Customize the Shortcode', 'colabsthemes' ); ?></h3>
    
	<table id="colabs-options-table">
	</table>

</div>

<div id="colabs-preview" class="alignleft">

    <h3><?php echo __( 'Preview', 'colabsthemes' ); ?></h3>

    <iframe id="colabs-preview-iframe" frameborder="0" style="width:100%;height:250px" scrolling="no"></iframe>   
    
</div>
<div class="clear"></div>


<script type="text/javascript" src="<?php echo $colabs_framework_url; ?>js/shortcode-generator/js/column-control.js"></script>
<script type="text/javascript" src="<?php echo $colabs_framework_url; ?>js/shortcode-generator/js/tab-control.js"></script>
<?php  }  else { ?>

<div id="colabs-options-error">

    <h3><?php echo __( 'Shortcode Generator Error', 'colabsthemes' ); ?></h3>
    
    <?php if ( $isCoLabsTheme && ( ! $meetsMinVersion ) ) { ?>
    <p><?php echo sprintf ( __( 'Your version of the CoLabsFramework (%s) does not yet support shortcodes. Shortcodes were introduced with version %s of the framework.', 'colabsthemes' ), $colabs_framework_version, $MIN_VERSION ); ?></p>
    
    <h4><?php echo __( 'What to do now?', 'colabsthemes' ); ?></h4>
    
    <p><?php echo __( 'Upgrading your theme, or rather the CoLabsFramework portion of it, will do the trick.', 'colabsthemes' ); ?></p>

	<p><?php echo sprintf( __( 'The framework is a collection of functionality that all CoLabsThemes have in common. In most cases you can update the framework even if you have modified your theme, because the framework resides in a separate location (under %s).', 'colabsthemes' ), '<code>/functions/</code>' ); ?></p>
	
	<p><?php echo sprintf ( __( 'There\'s a tutorial on how to do this on colorlabsproject.com: %sHow to upgradeyour theme%s.', 'colabsthemes' ), '<a title="ColorLabs Tutorial" target="_blank" href="http://colorlabsproject.com/">', '</a>' ); ?></p>
	
	<p><?php echo __( '<strong>Remember:</strong> Always backup your theme before you update it or make changes to it.', 'colabsthemes' ); ?></p>

<?php } else { ?>

    <p><?php echo __( 'Looks like your active theme is not from ColorLabs. The shortcode generator only works with themes from ColorLabs.', 'colabsthemes' ); ?></p>
    
    <h4><?php echo __( 'What to do now?', 'colabsthemes' ); ?></h4>

	<p><?php echo __( 'Pick a fight: (1) If you already have a theme from ColorLabs, install and activate it or (2) if you don\'t yet have one of the awesome ColorLabs theme head over to the <a href="http://colorlabsproject.com/themes/" target="_blank" title="ColorLabs Gallery">ColorLabs Gallery</a> and get one.', 'colabsthemes' ); ?></p>

<?php } ?>

<div style="float: right"><input type="button" id="colabs-btn-cancel"
	class="button" name="cancel" value="Cancel" accesskey="C" /></div>
</div>

<?php  } ?>

<script type="text/javascript" src="<?php echo $colabs_framework_url; ?>js/shortcode-generator/js/dialog-js.php"></script>

</div>

</body>
</html>