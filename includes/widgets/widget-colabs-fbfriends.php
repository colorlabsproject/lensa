<?php
// facebook like box sidebar widget
class CoLabs_Widget_Facebook extends WP_Widget {

    function CoLabs_Widget_Facebook() {
        $widget_ops = array( 'description' => __( 'This places a Facebook page Like Box in your sidebar to attract and gain Likes from visitors.', 'colabsthemes') );
        $this->WP_Widget(false, __('ColorLabs - Facebook Like Box', 'colabsthemes'), $widget_ops);
    }

    function widget( $args, $instance ) {

        extract($args);

        $title = apply_filters('widget_title', $instance['title'] );
		$fid = $instance['fid'];
		$connections = $instance['connections'];
		$width = $instance['width'];
		$height = $instance['height'];

        echo $before_widget;

		if ($title) echo $before_title . $title . $after_title;

        ?>
		<div class="pad5"></div>
        <iframe src="http://www.facebook.com/plugins/likebox.php?id=<?php echo $fid; ?>&amp;connections=<?php echo $connections; ?>&amp;stream=false&amp;header=true&amp;width=<?php echo $width; ?>&amp;height=<?php echo $height; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px;" allowTransparency="true"></iframe>
		<div class="pad5"></div>
        <?php

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
       $instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['fid'] = strip_tags( $new_instance['fid'] );
		$instance['connections'] = strip_tags($new_instance['connections']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);

		return $instance;
   }

   function form($instance) {

		$defaults = array( 'title' => __('Facebook Friends', 'colabsthemes'), 'fid' => '101618976863', 'connections' => '12', 'width' => '300', 'height' => '365' );
		$instance = wp_parse_args( (array) $instance, $defaults );
   ?>

        <p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'colabsthemes') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('fid'); ?>"><?php _e('Facebook ID:', 'colabsthemes') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('fid'); ?>" name="<?php echo $this->get_field_name('fid'); ?>" value="<?php echo $instance['fid']; ?>" />
		</p>

		<p style="text-align:left;">
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('connections'); ?>" name="<?php echo $this->get_field_name('connections'); ?>" value="<?php echo $instance['connections']; ?>" style="width:50px;" />
			<label for="<?php echo $this->get_field_id('connections'); ?>"><?php _e('Connections', 'colabsthemes') ?></label>
		</p>

		<p style="text-align:left;">
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" style="width:50px;" />
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width', 'colabsthemes') ?></label>
		</p>

		<p style="text-align:left;">
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>" style="width:50px;" />
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height', 'colabsthemes') ?></label>
		</p>

   <?php
   }
}

register_widget('CoLabs_Widget_Facebook');
?>