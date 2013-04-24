<?php get_header(); ?>
<div class="row">
    <header class="page-heading block-background block-inner">
      <h3><?php _e("Single","colabsthemes"); echo ' '.get_post_type(); ?></h3>
	  <div class="minimize"></div>
    </header><!-- .page-heading -->
  
    <div class="main-content block-background column col8">
	 <?php
	  if(have_posts()) : while(have_posts()): the_post();
	 ?>
      <div class="block-inner">
        <article class="entry-post">
          <header class="entry-header">
            <h2 class="entry-title"><?php the_title(); ?></h2>            
		    <?php colabs_post_meta(); ?>
          </header>
            
          <figure class="entry-media">
			<?php  
					$single_top = get_post_custom_values("colabs_single_top");
					if (($single_top[0]!='')||($single_top[0]=='none')){
						if ($single_top[0]=='single_video'){
							$embed = colabs_get_embed('colabs_embed',400,231,'single_video',$post->ID);
							if ($embed!=''){
								echo '<div class="single_video">'.$embed.'</div>'; 
							}
						}elseif($single_top[0]=='single_image'){
							colabs_image('width=620&link=img');				
						}										
					}
			?>
          </figure><!-- .entry-media -->
          
          <div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => __('<p><strong>Pages:</strong>','colabsthemes'), 'after' => '</p>', 'next_or_number' => 'number')); ?>
		  </div><!-- .entry-content -->
          
          <?php echo colabs_share(); ?>
        </article>
        
		<?php comments_template(); ?>
        
      </div><!-- .block-inner -->
	 <?php
	  endwhile;	  
	  endif;
	 ?>
    </div><!-- .main-content -->
    
	<?php get_sidebar(); ?>
    
  </div>
<?php get_footer(); ?>