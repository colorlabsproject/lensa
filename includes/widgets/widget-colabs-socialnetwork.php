<?php
/*---------------------------------------------------------------------------------*/
/* Social widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Social extends WP_Widget {

   function CoLabs_Social() {
	   $widget_ops = array('description' => 'set your social account on your theme panel.' );
       parent::WP_Widget(false, __('ColorLabs - Social', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );

	$title = apply_filters('widget_title', $instance['title'] );
	?>
		<?php echo $before_widget; ?>
        <?php if($title != '')echo $before_title .$title. $after_title;?>
		<?php if(function_exists('colabs_social_net')) { colabs_social_net(); } ?>
		<?php echo $after_widget; ?>   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        

	   $title = esc_attr($instance['title']);

       ?>

	    <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
      <?php
   }
} 

register_widget('CoLabs_Social');
?>