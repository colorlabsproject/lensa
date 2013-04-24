<?php
/*---------------------------------------------------------------------------------*/
/* CoLabs_Latest widget */
/*---------------------------------------------------------------------------------*/

class CoLabs_Latest extends WP_Widget {

   function CoLabs_Latest() {
       $widget_ops = array('description' => 'Use in Sidebar only.' );
       parent::WP_Widget(false, $name = __('ColorLabs - Latest', 'colabsthemes'), $widget_ops);    
   }


   function widget($args, $instance) {        
       extract( $args );
       
       $number = $instance['number']; if ($number == '') $number = 5;
       $title = $instance['title']; if ($title == '') $title = __('Latest Post','colabsthemes');
       
       echo $before_widget; 
       echo $before_title . $title . $after_title; 
	   ?>
		<div id="Latest">
            <?php 
			if (function_exists('colabs_tabs_latest')) colabs_tabs_latest($number);
			?>
        </div><!-- /colabsLatest -->
    
         <?php
         echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {                
       $title = esc_attr($instance['title']);
       $number = esc_attr($instance['number']);
       ?>    
       <p>
         <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?>
         <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
         </label>
       </p>
       <p>
         <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','colabsthemes'); ?>
         <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
         </label>
       </p> 
       <?php 
   }

} 
register_widget('CoLabs_Latest');

?>