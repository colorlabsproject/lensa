<?php
/**
 * Product template
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
      <h3><?php _e("Single","colabsthemes"); echo ' '.get_post_type(); ?></h3>
	  <div class="minimize"></div>
    </header><!-- .page-heading -->
  
    <div class="main-content block-background column col8">
	 <?php do_action('jigoshop_before_main_content'); ?>
	  
	  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); global $_product; $_product = new jigoshop_product( $post->ID ); ?>

		<?php do_action('jigoshop_before_single_product', $post, $_product); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php do_action('jigoshop_before_single_product_summary', $post, $_product); ?>

			<div class="summary entry-summary">

				<?php do_action( 'jigoshop_template_single_summary', $post, $_product ); ?>

			</div>

			<?php do_action('jigoshop_after_single_product_summary', $post, $_product); ?>

		</div>

		<?php do_action('jigoshop_after_single_product', $post, $_product); ?>

	  <?php endwhile; ?>
	  
	 <?php do_action('jigoshop_after_main_content'); ?>
    </div><!-- .main-content -->
    
	<?php do_action('jigoshop_sidebar'); ?>
    
  </div>
<?php get_footer(); ?>