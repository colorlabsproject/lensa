<?php
function colabs_admin_head_dashboard() {
    wp_enqueue_script('easytooltip', COLABS_FRAMEWORK_JS .'/jquery.easyTooltip.js', array('jquery'), '1.0');
    wp_enqueue_script('validate', COLABS_FRAMEWORK_JS .'/jquery.validate.pack.js', array('jquery'), '1.6');
    wp_enqueue_script('admin-scripts', COLABS_FRAMEWORK_JS .'/admin-scripts.js', array('jquery'), '1.0');

    wp_register_style('admin-dashboard-style', COLABS_FRAMEWORK_CSS .'/admin-dashboard-style.css', false, '1.0' );
    wp_enqueue_style('admin-dashboard-style');
}

function colabsthemes_dashboard_page() {
   global $wpdb;
   global $colabs_rss_feed, $colabs_twitter_rss_feed, $colabs_forum_rss_feed;

    $themename =  get_option( 'colabs_themename' );
    $manualurl =  get_option( 'colabs_manual' );
    $shortname = get_option( 'colabs_shortname' );
    $license = get_option('colabs_license');
    
    //Version in Backend Head
    $local_version = COLABS_THEME_VER;
	
    $framework_version = get_option( 'colabs_framework_version' );
	
   ?>

    <div class="wrap colabs_container">
        
        <div style="width: 80%;">
        <div class="themever left">
            <div id="icon-colabs" class="icon32"><br /></div>
            <h2><?php echo $themename; ?> <?php echo $local_version; ?>&nbsp;<?php _e( 'Theme Info','colabsthemes' ); ?></h2>
        </div>
        <div class="logocolabs right">
            <a href="http://colorlabsproject.com" title="Visit Our Website"><img src="<?php echo get_template_directory_uri(); ?>/functions/images/colorlabs.png" /></a>
        </div>
        <div class="clear"></div>
        </div>
        
        <?php colabs_admin_info_box(); ?>

        <div class="dash-left metabox-holder">

        <div class="postbox">
            <div class="statsico"></div>
            <h3 class="hndle"><span><?php echo $themename; ?>&nbsp;<?php _e('Info', 'colabsthemes') ?></span></h3>

            <div class="preloader-container">
                <div class="insider" id="boxy">

                    <ul>
                       <li><?php _e('Product Version', 'colabsthemes')?>: <strong><?php echo $local_version; ?></strong></li>
                       <li><?php _e('Framework Version', 'colabsthemes')?>: <strong><?php echo $framework_version; ?></strong></li>
                       <li><?php _e('Product Support', 'colabsthemes')?>:  <a href="http://colorlabsproject.com/resolve/" target="_new"><?php _e('Submit a Support Ticket','colabsthemes')?></a> | <a href="<?php echo $manualurl; ?>/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_new"><?php _e('Theme Documentation','colabsthemes')?></a> | <a href="http://ideas.colorlabsproject.com/" target="_blank">Suggest a Feature</a></li> 
                    </ul>

                </div><!--/.insider end -->
                
            </div><!--/.preloader-container end -->

        </div><!--/.postbox end -->



        <div class="postbox">
            <div class="newspaperico"></div><a target="_new" href="<?php echo $colabs_rss_feed ?>"><div class="rssico"></div></a>
            <h3 class="hndle" id="poststuff"><span><?php _e('ColorLabs News', 'colabsthemes') ?></span></h3>

            <div class="preloader-container">

                <div class="insider" id="boxy">

                    <?php colabs_dashboard(); ?>

                </div><!--/.insider end -->

            </div><!--/.preloader-container-->

        </div><!--/.postbox end -->

        <div class="clear"></div>
        </div><!-- dash-left end -->

        <div class="dash-right metabox-holder">

        <div class="postbox">
            <div class="twitterico"></div><a target="_new" href="<?php echo $colabs_twitter_rss_feed ?>"><div class="rssico"></div></a>
            <h3 class="hndle" id="poststuff"><span><?php _e('ColorLabs Tweets', 'colabsthemes') ?></span></h3>

            <div class="preloader-container">
                <div class="insider" id="boxy">

                    <?php colabs_dashboard_twitter(); ?>

                </div><!--/.inside end -->
                
            </div><!--/.preloader-container end -->

        </div><!--/.postbox end -->


        <div class="clear"></div>
        </div><!--/.dash-right end -->

        <div class="clear"></div>
    </div><!--/.wrap -->

<?php
}

function colabs_admin_info_box() {

    // reserved for future use
    

}

// CoLabs RSS blog feed for the dashboard page
function colabs_dashboard() {
global $colabs_rss_feed;
    wp_widget_rss_output($colabs_rss_feed, array('items' => 10, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 1));
}

// CoLabs RSS twitter feed for the dashboard page
function colabs_dashboard_twitter() {
global $colabs_twitter_rss_feed;
    wp_widget_rss_output($colabs_twitter_rss_feed, array('items' => 5, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 0));
}

// set the current params of CoLabs
$colabs_rss_feed = 'http://feeds2.feedburner.com/colorlabsproject';
$colabs_twitter_rss_feed = 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=colorlabs';
$colabs_forum_rss_feed = '';
?>
