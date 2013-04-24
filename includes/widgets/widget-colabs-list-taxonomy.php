<?php
/*---------------------------------------------------------------------------------*/
/* Taxonomy Categories widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Taxonomy_Categories extends WP_Widget {

   function CoLabs_Taxonomy_Categories() {
	   $widget_ops = array('description' => 'List Taxonomies.' );
       parent::WP_Widget(false, __('ColorLabs - Taxonomies', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title'];
	$taxonomy = $instance['widgettaxonomy'];
	if($taxonomy=='')$taxonomy='category';
	?>
		<?php echo $before_widget; ?>
        <?php if ($title) { echo $before_title . $title . $after_title; }else {echo $before_title .__('List Taxonomies','colabsthemes'). $after_title;} ?>
        <?php 
		echo '<ul>';
		wp_list_categories('taxonomy='.$taxonomy.'&title_li='); 
		echo '</ul>';
		?>
		<?php echo $after_widget; ?>   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
	   $widgettaxonomy = esc_attr($instance['widgettaxonomy']);

       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
	   <p>
	   	   <label for="<?php echo $this->get_field_id('widgettaxonomy'); ?>"><?php _e('Taxonomy:','colabsthemes'); ?></label>
		   <select class="widefat" name="<?php echo $this->get_field_name('widgettaxonomy'); ?>">
				<?php
				$args=array(
				  'public'   => true				  
				); 
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies=get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
				  foreach ($taxonomies  as $itemtaxonomy ) {
					if ($itemtaxonomy==$widgettaxonomy)$selected='selected="selected"';
					echo '<option value="'. $itemtaxonomy. '" '.$selected.'>'.$itemtaxonomy.'</option>';
					$selected='';
				  }
				}
				?>	
			</select>
	       
       </p>
      <?php
   }
} 

register_widget('CoLabs_Taxonomy_Categories');
?>