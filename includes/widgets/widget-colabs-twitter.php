<?php
/*---------------------------------------------------------------------------------*/
/* Twitter widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Twitter extends WP_Widget {

   function CoLabs_Twitter() {
	   $widget_ops = array('description' => 'Show your Twitter feed.' );
       parent::WP_Widget(false, __('ColorLabs - Twitter Stream', 'colabsthemes'),$widget_ops);      
   }
   
   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title'] ;
    $limit = $instance['limit']; if (!$limit) $limit = 5;
	$username = $instance['username'];
	$unique_id = $args['widget_id'];
	?>
		<?php echo $before_widget; ?>
        <?php if($title!='') echo $before_title . $title . $after_title;?>
        
        <div class="back">
          <ul id="twitter_update_list_<?php echo $unique_id; ?>"><li></li></ul>
          <p><a href="http://twitter.com/<?php echo $username; ?>" class="button blue">
            <?php _e('Follow','colabsthemes'); ?> @<?php echo $username; ?>
          </a></p>
		    </div>
	<div class="clear"></div>
        <?php echo colabs_twitter_script($unique_id,$username,$limit); //Javascript output function ?>	 
        <?php echo $after_widget; ?>
        
   		
	<?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
       $limit = esc_attr($instance['limit']);
	   $username = esc_attr($instance['username']);
       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('username'); ?>"  value="<?php echo $username; ?>" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('limit'); ?>"  value="<?php echo $limit; ?>" class="" size="3" id="<?php echo $this->get_field_id('limit'); ?>" />

       </p>
      <?php
   }
   
} 
register_widget('CoLabs_Twitter');
?>
