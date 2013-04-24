<?php
/*---------------------------------------------------------------------------------*/
/* Flickr widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_flickr extends WP_Widget {

	function CoLabs_flickr() {
		$widget_ops = array('description' => 'A Flickr widget that populates photos from a Flickr ID, use in Sidebar only.' );

		parent::WP_Widget(false, __('ColorLabs - Flickr', 'colabsthemes'),$widget_ops);      
	}

	function widget($args, $instance) {  
		extract( $args );
        $title = apply_filters('widget_title', $instance['title'] );
		$id = $instance['id'];
		$number = $instance['number'];
		$type = $instance['type'];
		$sorting = $instance['sorting'];
		$size = $instance['size'];
		
		echo $before_widget;
		echo $before_title; ?>
		<?php if( $title ){ echo $title; }else{ _e('Photos on <span>flick<span>r</span></span>','colabsthemes'); } ?>
        <?php echo $after_title; ?>
            
        <div class="flickr">
            <div class="clear"></div>
            <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=<?php echo $sorting; ?>&amp;&amp;layout=h&amp;source=<?php echo $type; ?>&amp;<?php echo $type; ?>=<?php echo $id; ?>&amp;size=<?php echo $size; ?>"></script>        
            <div class="clear"></div>
        </div>

	   <?php			
	   echo $after_widget;
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {
        $title = esc_attr($instance['title']);
		$id = esc_attr($instance['id']);
		$number = esc_attr($instance['number']);
		$type = esc_attr($instance['type']);
		$sorting = esc_attr($instance['sorting']);
		$size = esc_attr($instance['size']);
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>        
        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo $id; ?>" class="widefat" id="<?php echo $this->get_field_id('id'); ?>" />
        </p>
       	<p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:','colabsthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('number'); ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>">
                <?php for ( $i = 1; $i <= 10; $i += 1) { ?>
                <option value="<?php echo $i; ?>" <?php if($number == $i){ echo "selected='selected'";} ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Type:','colabsthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('type'); ?>" class="widefat" id="<?php echo $this->get_field_id('type'); ?>">
                <option value="user" <?php if($type == "user"){ echo "selected='selected'";} ?>><?php _e('User', 'colabsthemes'); ?></option>
                <option value="group" <?php if($type == "group"){ echo "selected='selected'";} ?>><?php _e('Group', 'colabsthemes'); ?></option>            
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sorting'); ?>"><?php _e('Sorting:','colabsthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('sorting'); ?>" class="widefat" id="<?php echo $this->get_field_id('sorting'); ?>">
                <option value="latest" <?php if($sorting == "latest"){ echo "selected='selected'";} ?>><?php _e('Latest', 'colabsthemes'); ?></option>
                <option value="random" <?php if($sorting == "random"){ echo "selected='selected'";} ?>><?php _e('Random', 'colabsthemes'); ?></option>            
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size:','colabsthemes'); ?></label>
            <select name="<?php echo $this->get_field_name('size'); ?>" class="widefat" id="<?php echo $this->get_field_id('size'); ?>">
                <option value="s" <?php if($size == "s"){ echo "selected='selected'";} ?>><?php _e('Square', 'colabsthemes'); ?></option>
                <option value="m" <?php if($size == "m"){ echo "selected='selected'";} ?>><?php _e('Medium', 'colabsthemes'); ?></option>
                <option value="t" <?php if($size == "t"){ echo "selected='selected'";} ?>><?php _e('Thumbnail', 'colabsthemes'); ?></option>
            </select>
        </p>
		<?php
	}
} 

register_widget('colabs_flickr');
?>