<?php
/**
 * Displays the contents of the README file.
 *
 * Returns contents of the README.txt Child theme file, if it exists.
 *
 *
 * 
 * colabsthemes_readme_menu_admin()
 */


//Readme Load Scripts
function readme_register_admin_head(){
    
    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/functions/admin-style.css" media="screen" />';

}//END function readme_register_admin_head

//Readme Admin Menu
function colabsthemes_readme_menu_admin() {

    // Get Theme Name   
    $themename =  get_option( 'colabs_themename' );
    
	// Assume we cannot find the file.
	$file = array();

	// Get the file contents
	$file = wp_remote_get( get_template_directory_uri() . '/README.txt');


?>
<div id="colabs_options" class="wrap<?php if (is_rtl()) { echo ' rtl'; } ?>">

	<div class="one_col wrap colabs_container">
    
	<div id="main">
        
	<div id="panel-header">
        <?php colabsthemes_options_page_header('save_button=false'); ?>
	</div><!-- #panel-header -->

    <div id="panel-content">

    <div class="section">
    <h3 class="heading"><?php echo $themename.__( ' - README.txt Theme File', 'colabsthemes'); ?></h3>
    <div class="option">

    	<div id="colabs-readme-file">
    		<?php 
			if (isset($file)){
			echo wpautop( $file['body']); 
			}else{
			echo '<b>README.txt file not found.</b>';
			}
			?>
    	</div>
		
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

<?php
}
