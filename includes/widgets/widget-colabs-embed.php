<?php
/*---------------------------------------------------------------------------------*/
/* Embed Widget */
/*---------------------------------------------------------------------------------*/


class CoLabs_EmbedWidget extends WP_Widget {

	function CoLabs_EmbedWidget() {
		$widget_ops = array('description' => 'Display the Embed code from posts, use in Sidebar only.' );
		parent::WP_Widget(false, __('ColorLabs - Embed/Video', 'colabsthemes'),$widget_ops);      
	}

	function widget($args, $instance) { 
		extract( $args ); 
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Video Posts','colabsthemes') : $instance['title'] );
		$limit = $instance['limit'];
		
		$cat_id = $instance['cat_id'];
		$tag = $instance['tag'];
		
		$width = $instance['width'];
		$height = $instance['height'];

		if(!empty($tag))
			$myposts = get_posts("numberposts=$limit&tag=$tag");
		else
			$myposts = get_posts("numberposts=$limit&cat=$cat_id");

		if(empty($limit)) $limit = 10;
		if(empty($width)) $width = 300;
		if(empty($height)) $height = 182;
        
		$post_list = '';
		$count = 0;
		$active = "active";
		$display = "";

        echo $before_widget; ?>
       
        <?php

			echo $before_title .$title. $after_title; ?>
            <div class="clear"></div>
            <?php    
		
			if(isset($myposts)) {
			
				foreach($myposts as $mypost) {
					
					$embed = colabs_get_embed('colabs_embed',$width,$height,'widget_video',$mypost->ID);

					if($embed) {
						$count++;
						if($count > 1) {$active = ''; $display = "style='display:none'"; }
						?>
						<div class="widget-video-unit" <?php echo $display; ?> >
						<?php
							echo '<h6><a href="'. get_permalink($mypost->ID) .'" title="'.__('Read Article','colabsthemes').'">' . get_the_title($mypost->ID)  . "</a></h6>\n";
							
							echo $embed;
							
							$post_list .= "<li class='$active'><a href='#'>" . get_the_title($mypost->ID) . "</a></li>\n";
						?>
						</div>
						<?php
					}
				}
			}
		?>
        <ul class="widget-video-list">
        	<?php echo $post_list; ?>
        </ul>

        <?php
			
		echo $after_widget;

	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		$title = esc_attr($instance['title']);
		$limit = esc_attr($instance['limit']);
		$cat_id = esc_attr($instance['cat_id']);
		$tag = esc_attr($instance['tag']);

		$width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
		
		if(empty($limit)) $limit = 10;
		if(empty($width)) $width = 300;
		if(empty($height)) $height = 182;

		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('cat_id'); ?>"><?php _e('Category:','colabsthemes'); ?></label>
	       <?php $cats = get_categories(); ?>
	       <select name="<?php echo $this->get_field_name('cat_id'); ?>" class="widefat" id="<?php echo $this->get_field_id('cat_id'); ?>">
           <option value="">Disabled</option>
			<?php
			
           	foreach ($cats as $cat){
           	?><option value="<?php echo $cat->cat_ID; ?>" <?php if($cat_id == $cat->cat_ID){ echo "selected='selected'";} ?>><?php echo $cat->cat_name . ' (' . $cat->category_count . ')'; ?></option><?php
           	}
           ?>
           </select>
       </p>
        <p>
            <label for="<?php echo $this->get_field_id('tag'); ?>">Or <?php _e('Tag:','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('tag'); ?>" value="<?php echo $tag; ?>" class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" />
        </p>

         <p>
            <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Size:','colabsthemes'); ?></label>
            <input type="text" size="2" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $width; ?>" class="" id="<?php echo $this->get_field_id('width'); ?>" /> W
            <input type="text" size="2" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $height; ?>" class="" id="<?php echo $this->get_field_id('height'); ?>" /> H

        </p>
        
         <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit (optional):','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $limit; ?>" class="" id="<?php echo $this->get_field_id('limit'); ?>" />
        </p>

        <?php
	}
}

register_widget('CoLabs_EmbedWidget');

if(is_active_widget( null,null,'colabs_embedwidget' ) == true) {
	add_action('wp_footer','colabs_widget_embed_js');
}

function colabs_widget_embed_js(){
?>
<!-- CoLabs Video Player Widget -->
<script type="text/javascript">
	jQuery(document).ready(function(){
		var list = jQuery('ul.widget-video-list');
		list.find('a').click(function(){
			var clickedTitle = jQuery(this).text();
			jQuery(this).parent().parent().find('li').removeClass('active');
			jQuery(this).parent().addClass('active');
			var videoHolders = jQuery(this).parent().parent().parent().children('.widget-video-unit');
			videoHolders.each(function(){
				if(clickedTitle == jQuery(this).children('h4').text()){
					videoHolders.hide();
					jQuery(this).show();
				}
			})
			return false;
		})
	})
</script>
<?php
}


?>