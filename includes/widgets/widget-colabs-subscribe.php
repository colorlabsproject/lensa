<?php
/*---------------------------------------------------------------------------------*/
/* Subscribe widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Subscribe extends WP_Widget {

   function CoLabs_Subscribe() {
	   $widget_ops = array('description' => 'Subscribe by Mail Widget.' );
       parent::WP_Widget(false, __('ColorLabs - Subscribe by Mail', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title'];
	$feedid = $instance['feedid'];
	$width = $instance['width'];
    $height = $instance['height'];
	$desc = $instance['desc'];
	?>
		<?php echo $before_widget; ?>
        <?php echo $before_title;?>
        <?php if ($title) { echo $title; }else{ _e('Subscribe to Our Newsletter','colabsthemes'); } ?>
		<?php echo $after_title;  ?>
        <?php if($desc)echo '<p>'.$desc.'</p>';?>
	   <form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feedid; ?>', 'popupwindow', 'scrollbars=no,width=<?php echo $width; ?>,height=<?php echo $height; ?>');return true">
		<input type="text"  name="email" value="<?php _e('Your Email Here','colabsthemes'); ?>" onclick="this.focus();this.select();"/>
		<input type="hidden" value="<?php echo $feedid; ?>" name="uri"/>
		<input type="hidden" name="loc" value="en_US"/>
		<input type="submit" class="btn" value="<?php _e('Submit','colabsthemes'); ?>" />
		
	   </form>        
        
		<?php echo $after_widget; ?>   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
        $title = esc_attr($instance['title']);
        $feedid = esc_attr($instance['feedid']);
        $width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
		$desc = esc_attr($instance['desc']);
		
		if(empty($width)) $width = 300;
		if(empty($height)) $height = 220;
       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
	    <p>
	   	   <label for="<?php echo $this->get_field_id('feedid'); ?>"><?php _e('Feedburner ID :','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('feedid'); ?>"  value="<?php echo $feedid; ?>" class="widefat" id="<?php echo $this->get_field_id('feedid'); ?>" />
       </p>
	    <p>
	   	   <label for="<?php echo $this->get_field_id('desc'); ?>"><?php _e('Description:','colabsthemes'); ?></label>
		   <textarea name="<?php echo $this->get_field_name('desc'); ?>" id="<?php echo $this->get_field_id('desc'); ?>" cols="6" rows="16" class="widefat"><?php echo $desc; ?></textarea>	
	  </p>
         <p>
            <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Form Size:','colabsthemes'); ?></label>
            <input type="text" size="2" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $width; ?>" class="" id="<?php echo $this->get_field_id('width'); ?>" /> W
            <input type="text" size="2" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $height; ?>" class="" id="<?php echo $this->get_field_id('height'); ?>" /> H

        </p>       
      <?php
   }
} 

register_widget('CoLabs_Subscribe');

?>
