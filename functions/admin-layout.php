<?php
/*-----------------------------------------------------------------------------------*/
/* Layout Settings page - colabsthemes_layout_settings_page */
/*-----------------------------------------------------------------------------------*/

$layoutfile = get_template_directory() . '/includes/theme-options-layout.php';
if( file_exists($layoutfile) ) require_once ( $layoutfile ); // Custom theme layout options
    
function colabsthemes_layout_settings_page(){
    $layout_options =  get_option( 'colabs_layout_template' );      
    $themename =  get_option( 'colabs_themename' );
    $manualurl =  get_option( 'colabs_manual' );
    $shortname =  'colabs_layout';

    //Framework Version in Backend Head
    $colabs_framework_version = get_option( 'colabs_framework_version' );


    //GET themes update RSS feed and do magic
    include_once(ABSPATH . WPINC . '/feed.php' );

    $pos = strpos($manualurl, 'documentation' );
    $theme_slug = str_replace( "/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation

    //add filter to make the rss read cache clear every 4 hours
    add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 14400;' ) );
    
    ?>

    <div class="wrap colabs_container">
        <form action="" enctype="multipart/form-data" id="colabsform" method="post">
        <?php
            // Add nonce for added security.
            if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-layout-options-update' ); } // End IF Statement

            $colabs_nonce = '';

            if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-layout-options-update' ); } // End IF Statement

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
            <?php $return = colabsthemes_machine($layout_options); ?>
            <div id="main">
                <div id="panel-header">
                    <?php colabsthemes_options_page_header('reset_button=false'); ?>
                </div><!-- #panel-header -->
                
                <div id="sidebar-nav">
                    <ul>
                        <?php echo $return[1]; ?>
                    </ul>
                </div>
                <div id="panel-content">
                    <div class="group help-block"> <p><?php _e("Drag icon on the left and Drop it here to customize","colabsthemes"); ?></p> </div>
                    <?php echo $return[0]; ?>
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
                        <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>" method="post" style="display:inline" id="colabsform-reset">
                        <?php
                            // Add nonce for added security.
                            if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-layout-options-reset' ); } // End IF Statement

                            $colabs_nonce = '';

                            if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-layout-options-reset' ); } // End IF Statement

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

            </div><!-- #main -->

    <div style="clear:both;"></div>
    </div><!--wrap-->

<?php } ?>