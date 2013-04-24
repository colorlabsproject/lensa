<?php
/*
Template Name: Blog
*/
?>
<?php get_header(); ?>
<div class="row">
    <header class="page-heading block-background block-inner">
      <h3><?php wp_title(' ', true,'right'); ?></h3>
	  <div class="minimize"></div>
    </header><!-- .page-heading -->
  
    <div class="main-content block-background column col8">
      <div class="block-inner">
	 <?php
		global $wp_query;
		$args = array_merge( $wp_query->query_vars, array( 'post_type' => 'photograph' ) );
		query_posts( $args );
	  if(have_posts()) : while(have_posts()): the_post();
	 ?>
        <article class="entry-post">
          <header class="entry-header">
            <h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>            
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
					}else{
						colabs_image('width=620&link=img');		
					}
			?>
          </figure><!-- .entry-media -->
          
          <div class="entry-content">
			<?php colabs_custom_excerpt(); ?>
			<p class="more"><a href="<?php the_permalink() ?>"><?php _e("More","colabsthemes"); ?></a></p>
		  </div><!-- .entry-content -->
          
        </article>
        
	 <?php
	  endwhile;	
	  colabs_pagenav();
	  endif;
	 ?>
      </div><!-- .block-inner -->
    </div><!-- .main-content -->
    
	<?php get_sidebar(); ?>
    
  </div>
<?php get_footer(); ?>