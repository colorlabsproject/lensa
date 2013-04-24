<?php
/*---------------------------------------------------------------------------------*/
/* CoLabsTabs widget */
/*---------------------------------------------------------------------------------*/

class CoLabs_Tabs extends WP_Widget {

   function CoLabs_Tabs() {
       $widget_ops = array('description' => 'Tabbed lists containing popular posts, recent comments and a tag cloud. Use in Sidebar only.' );
       parent::WP_Widget(false, $name = __('ColorLabs - Tabs', 'colabsthemes'), $widget_ops);    
   }


   function widget($args, $instance) {        
       extract( $args );
       
       $number = $instance['number']; if ($number == '') $number = 5;
       $thumb_size = $instance['thumb_size']; if ($thumb_size == '') $thumb_size = 35;
     $order = $instance['order']; if ($order == '') $order = "pop";
     $pop = ''; if ( array_key_exists( 'pop', $instance ) ) $pop = $instance['pop'];

     $comments = ''; if ( array_key_exists( 'comments', $instance ) ) $comments = $instance['comments'];
     $tags = ''; if ( array_key_exists( 'tags', $instance ) ) $tags = $instance['tags'];
       
       echo $before_widget; 
       ?>  

    <div id="tabs">
           
            <ul class="colabsTabs">
                <?php if ( $order == "latest" && !$latest == "on") { ?><li class="latest"><a href="#tab-latest"><?php _e('Latest', 'colabsthemes'); ?></a></li>
                <?php } elseif ( $order == "comments" && !$comments == "on") { ?><li class="comments"><a href="#tab-comm"><?php _e('Comments', 'colabsthemes'); ?></a></li>
                <?php } elseif ( $order == "tags" && !$tags == "on") { ?><li class="tags"><a href="#tab-tags"><?php _e('Tags', 'colabsthemes'); ?></a></li>
                <?php } ?>
                <?php if (!$pop == "on") { ?><li class="popular"><a href="#tab-pop"><?php _e('Popular', 'colabsthemes'); ?></a></li><?php } ?>
               
                <?php if ($order <> "comments" && !$comments == "on") { ?><li class="comments"><a href="#tab-comm"><?php _e('Comments', 'colabsthemes'); ?></a></li><?php } ?>
                <?php if ($order <> "tags" && !$tags == "on") { ?><li class="tags"><a href="#tab-tags"><?php _e('Tags', 'colabsthemes'); ?></a></li><?php } ?>
            </ul>
            
            <div class="clear"></div>
            
            <div class="boxes box inside">
                        
              <?php if ( $order == "comments" && !$comments == "on") { ?>
        <ul id="tab-comm" class="list">
                    <?php if ( function_exists('colabs_tabs_comments') ) colabs_tabs_comments($number, $thumb_size); ?>                    
                </ul>
              <?php } elseif ( $order == "tags" && !$tags == "on") { ?>
                <div id="tab-tags" class="list">
                    <?php wp_tag_cloud('smallest=12&largest=20'); ?>
                </div>
                <?php } ?>
                
                <?php if (!$pop == "on") { ?>
                <ul id="tab-pop" class="list">            
                    <?php if ( function_exists('colabs_tabs_popular') ) colabs_tabs_popular($number, $thumb_size); ?>                    
                </ul>
                <?php } ?>
                <?php if ($order <> "comments" && !$comments == "on") { ?>
        <ul id="tab-comm" class="list">
                    <?php if ( function_exists('colabs_tabs_comments') ) colabs_tabs_comments($number, $thumb_size); ?>                    
                </ul>                
                <?php } ?>
                <?php if ($order <> "tags" && !$tags == "on") { ?>
                <div id="tab-tags" class="list">
                    <?php wp_tag_cloud('smallest=12&largest=20'); ?>
                </div>                
                <?php } ?>

            </div><!-- /.boxes -->
      
        </div><!-- /colabsTabs -->
    
         <?php
         echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {                
       $number = esc_attr($instance['number']);
       $thumb_size = esc_attr($instance['thumb_size']);
       $order = esc_attr($instance['order']);
       $pop = esc_attr($instance['pop']);
       $comments = esc_attr($instance['comments']);
       $tags = esc_attr($instance['tags']);

       ?>    
       <p>
         <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','colabsthemes'); ?>
         <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
         </label>
       </p>  <!--
       <p>
         <label for="<?php echo $this->get_field_id('thumb_size'); ?>"><?php _e('Thumbnail Size (0=disable):','colabsthemes'); ?>
         <input class="widefat" id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>" type="text" value="<?php echo $thumb_size; ?>" />
         </label>
       </p>  -->
        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('First Visible Tab:','colabsthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('order'); ?>" class="widefat" id="<?php echo $this->get_field_id('order'); ?>">
                <option value="pop" <?php if($order == "pop"){ echo "selected='selected'";} ?>><?php _e('Popular', 'colabsthemes'); ?></option>
                <option value="comments" <?php if($order == "comments"){ echo "selected='selected'";} ?>><?php _e('Comments', 'colabsthemes'); ?></option>
                <option value="tags" <?php if($order == "tags"){ echo "selected='selected'";} ?>><?php _e('Tags', 'colabsthemes'); ?></option>
            </select>
        </p>
       <p><strong>Hide Tabs:</strong></p>
       <p>
        <input id="<?php echo $this->get_field_id('pop'); ?>" name="<?php echo $this->get_field_name('pop'); ?>" type="checkbox" <?php if($pop == 'on') echo 'checked="checked"'; ?>><?php _e('Popular', 'colabsthemes'); ?></input>
     </p>
     <p>
         <input id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" type="checkbox" <?php if($comments == 'on') echo 'checked="checked"'; ?>><?php _e('Comments', 'colabsthemes'); ?></input>
     </p>
     <p>
         <input id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="checkbox" <?php if($tags == 'on') echo 'checked="checked"'; ?>><?php _e('Tags', 'colabsthemes'); ?></input>
       </p>  
       <?php 
   }

} 
register_widget('CoLabs_Tabs');

// Add Javascript
if(is_active_widget( null,null,'colabs_tabs' ) == true) {
  add_action('wp_footer','colabs_widget_tabs_js');
}

function colabs_widget_tabs_js(){
?>
<!-- CoLabs Tabs Widget -->
<script type="text/javascript">
jQuery(document).ready(function(){
  // UL = .colabsTabs
  // Tab contents = .inside
  
  var tag_cloud_class = '#tagcloud'; 
  
  //Fix for tag clouds - unexpected height before .hide() 
  var tag_cloud_height = jQuery('#tagcloud').height();
  
  //jQuery('.inside ul li:last-child').css('border-bottom','0px'); // remove last border-bottom from list in tab content
  jQuery('.colabsTabs').each(function(){
    jQuery(this).children('li').children('a:first').addClass('selected'); // Add .selected class to first tab on load
  });
  jQuery('.inside > *').hide();
  jQuery('.inside > *:first-child').show();
  
  jQuery('.colabsTabs li a').click(function(evt){ // Init Click funtion on Tabs
    evt.preventDefault();
    var clicked_tab_ref = jQuery(this).attr('href'); // Strore Href value
    
    jQuery(this).parent().parent().children('li').children('a').removeClass('selected'); //Remove selected from all tabs
    jQuery(this).addClass('selected');
    jQuery(this).parent().parent().parent().children('.inside').children('*').hide();
    
    jQuery('.inside ' + clicked_tab_ref).fadeIn(500);
  
  });
});
</script>
<?php
}

?>