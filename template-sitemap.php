<?php
/*
Template Name: Sitemap
*/
?>

<?php get_header(); ?>
<div class="row">
    <header class="page-heading block-background block-inner">
      <h3><?php the_title(); ?></h3>
	  <div class="minimize"></div>
    </header><!-- .page-heading -->
  
    <div class="main-content block-background column col8">
      <div class="block-inner">
        <article class="entry-post">        
          <div class="entry-content">			
			  <div class="entry-sitemap column col6">
			  <h4><?php _e('Pages:','colabsthemes');?></h4>
			  <ul>
				<?php wp_list_pages('depth=1&title_li='); ?>
			  </ul>
			  </div>
			  
			  <div class="entry-sitemap column col6">
			  <h4><?php _e('Blog Categories:','colabsthemes');?></h4>
			  <ul>
				<?php wp_list_categories('title_li=&hierarchical=0&show_count=1'); ?>
			  </ul>
			  </div>
			  
			  <div class="entry-sitemap column col6">
			  <h4><?php _e('Monthly Archives:','colabsthemes');?></h4>
			  <ul>
				<?php wp_get_archives('type=monthly'); ?>
			  </ul>
			  </div>
			  
			   <div class="entry-sitemap column col6">
			  <h4><?php _e('RSS Feed:','colabsthemes');?></h4>
			  <ul>
				<li><a href="<?php bloginfo('rdf_url'); ?>" title="RDF/RSS 1.0 feed"><acronym title="Resource Description Framework">RDF</acronym>/<acronym title="Really Simple Syndication">RSS</acronym> 1.0 feed</a></li>
				<li><a href="<?php bloginfo('rss_url'); ?>" title="RSS 0.92 feed"><acronym title="Really Simple Syndication">RSS</acronym> 0.92 feed</a></li>
				<li><a href="<?php bloginfo('rss2_url'); ?>" title="RSS 2.0 feed"><acronym title="Really Simple Syndication">RSS</acronym> 2.0 feed</a></li>
				<li><a href="<?php bloginfo('atom_url'); ?>" title="Atom feed">Atom feed</a></li>
			  </ul>
			  </div>
			  
			  <div class="entry-sitemap column col6">
			  <h4><?php _e('Photograph Categories:','colabsthemes');?></h4>
			  <ul>
				<?php 
					$args = array(
						'taxonomy'     => 'photograph-categories',
						'orderby'      => 'name',
						'show_count'   => 1,
						'pad_counts'   => 1,
						'hierarchical' => 1,
						'title_li'     => ''
					);
					wp_list_categories($args) 
					?>
			  </ul>
			  </div>
			
		  </div><!-- .entry-content -->
          
        </article>
        
        
      </div><!-- .block-inner -->
    </div><!-- .main-content -->
    
	<?php get_sidebar(); ?>
    
  </div>
<?php get_footer(); ?>