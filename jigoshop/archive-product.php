<?php
/**
 * Archive template
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package             Jigoshop
 * @category            Catalog
 * @author              Jigoshop
 * @copyright           Copyright © 2011-2013 Jigoshop.
 * @license             http://jigoshop.com/license/commercial-edition
 */
?>
<?php
/**
 * Product taxonomy template
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package             Jigoshop
 * @category            Catalog
 * @author              Jigoshop
 * @copyright           Copyright © 2011-2013 Jigoshop.
 * @license             http://jigoshop.com/license/commercial-edition
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>
  <div class="row">
    <header class="page-heading block-background block-inner">
      <h3><?php _e("Archive","colabsthemes"); echo ' '.get_post_type(); ?></h3>
	  <div class="minimize"></div>
    </header><!-- .page-heading -->
  
    <div class="main-content block-background column col12">
	 <?php do_action('jigoshop_before_main_content'); ?>
		
		<?php if (is_search()) : ?>
			<h1 class="page-title"><?php _e('Search Results:', 'jigoshop'); ?> &ldquo;<?php the_search_query(); ?>&rdquo; <?php if (get_query_var('paged')) echo ' &mdash; Page '.get_query_var('paged'); ?></h1>
		<?php else : ?>
			<h1 class="page-title"><?php _e('All Products', 'jigoshop'); ?></h1>
		<?php endif; ?>

		<?php
			/* $shop_page_id = jigoshop_get_page_id('shop');
			$shop_page = get_post($shop_page_id);
			echo apply_filters('the_content', $shop_page->post_content); */
		?>

		<?php jigoshop_get_template_part( 'loop', 'shop' ); ?>

		<?php do_action('jigoshop_pagination'); ?>


	<?php do_action('jigoshop_after_main_content'); ?>
    </div><!-- .main-content -->
        
  </div>
<?php get_footer(); ?>