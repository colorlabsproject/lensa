<?php
/* Template Name: Gallery */

get_header(); 
?>	
<?php get_header(); ?>
<div class="row">
    <header class="page-heading block-background block-inner">
      <h3><?php the_title(); ?></h3>
	  <div class="minimize"></div>
    </header><!-- .page-heading -->
  
    <div class="main-content block-background column col12">
      <div class="block-inner">
	   <ul class="gallery-list">  
		<?php 
		
		if(get_post_meta($post->ID,'meta_style_gallery',true)=='facebook'){
			get_template_part('template','facebook');	
		}elseif(get_post_meta($post->ID,'meta_style_gallery',true)=='instagram'){
			get_template_part('template','instagram');	
		}elseif(get_post_meta($post->ID,'meta_style_gallery',true)=='pinterest'){
			get_template_part('template','pinterest');	
		}elseif(get_post_meta($post->ID,'meta_style_gallery',true)=='flickr'){	
			get_template_part('template','flickr');	
		}elseif(get_post_meta($post->ID,'meta_style_gallery',true)=='picasa'){			
			get_template_part('template','picasa');	
		}elseif(get_post_meta($post->ID,'meta_style_gallery',true)=='photograph'){
			query_posts(array('post_type' => 'photograph', 'paged' => $paged ));
			if(have_posts()):
				get_template_part('template','photograph');	
			endif;
		}else{
			$cat_id = get_post_meta($post->ID, "cat",true);
			if($cat_id != -1):
				query_posts(array('post_type' => 'post', 'paged' => $paged, 'cat' => $cat_id));
			else:
				query_posts(array('post_type' => 'post', 'paged' => $paged ));
			endif;
			if(have_posts()):
				get_template_part('template','post');	
			endif;		
		}	
		
		//wp_reset_query();
        ?>
	   </ul>
		  <?php if ( $wp_query->max_num_pages > 1 ) : ?>
				<div class="colabs-pagination">
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'colabthemes' ) ); ?></div>
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'colabthemes' ) ); ?></div>
				</div>	
		  <?php endif; ?>
			
			<?php 
			// Navigation for flickr gallery
			if( get_post_meta($post->ID,'meta_style_gallery',true) == 'flickr') : ?>
				<?php 
				global $user_photo;
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				if( $user_photo['total_pages'] > 1 && $paged <= $user_photo['total_pages'] ) : ?>
					<div class="colabs-pagination">

						<?php if( $paged < $user_photo['total_pages'] ) : ?>
							<div class="nav-next"><a href="<?php echo add_query_arg( array('paged' => $paged + 1), get_permalink( $post->ID ) ); ?>"><?php echo __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'colabthemes' ); ?></a></div>
						<?php endif; ?>

						<?php if( $paged > 1 ) : ?>
							<div class="nav-previous"><a href="<?php echo add_query_arg( array('paged' => $paged - 1), get_permalink( $post->ID ) ); ?>"><?php echo __( '<span class="meta-nav">&larr;</span> Older posts', 'colabthemes' ); ?></a></div>
						<?php endif; ?>

					</div>
				<?php endif; ?>
			<?php endif; ?>

      </div><!-- .block-inner -->
    </div><!-- .main-content -->
    
  </div>
<?php get_footer(); ?>