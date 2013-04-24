<?php

/*-------------------------------------------------------------------------------------

TABLE OF CONTENTS

- Hook Definitions

- Contextual Hook and Filter Functions
-- colabs_do_atomic()
-- colabs_apply_atomic()
-- colabs_get_query_context()

-------------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Hook Definitions */
/*-----------------------------------------------------------------------------------*/

// header.php
function colabs_head() { colabs_do_atomic( 'colabs_head' ); }					
function colabs_top() { colabs_do_atomic( 'colabs_top' ); }					
function colabs_header_before() { colabs_do_atomic( 'colabs_header_before' ); }			
function colabs_header_inside() { colabs_do_atomic( 'colabs_header_inside' ); }				
function colabs_header_after() { colabs_do_atomic( 'colabs_header_after' ); }			
function colabs_nav_before() { colabs_do_atomic( 'colabs_nav_before' ); }					
function colabs_nav_inside() { colabs_do_atomic( 'colabs_nav_inside' ); }					
function colabs_nav_after() { colabs_do_atomic( 'colabs_nav_after' ); }		

// Template files: 404, archive, single, page, sidebar, index, search
function colabs_content_before() { colabs_do_atomic( 'colabs_content_before' ); }
function colabs_content_after() { colabs_do_atomic( 'colabs_content_after' ); }					
function colabs_main_before() { colabs_do_atomic( 'colabs_main_before' ); }					
function colabs_main_after() { colabs_do_atomic( 'colabs_main_after' ); }					
function colabs_post_before() { colabs_do_atomic( 'colabs_post_before' ); }					
function colabs_post_after() { colabs_do_atomic( 'colabs_post_after' ); }					
function colabs_post_inside_before() { colabs_do_atomic( 'colabs_post_inside_before' ); }					
function colabs_post_inside_after() { colabs_do_atomic( 'colabs_post_inside_after' ); }
function colabs_post_content_before() { colabs_do_atomic( 'colabs_post_content_before' ); }					
function colabs_post_content_after() { colabs_do_atomic( 'colabs_post_content_after' ); }	
function colabs_loop_before() { colabs_do_atomic( 'colabs_loop_before' ); }	
function colabs_loop_after() { colabs_do_atomic( 'colabs_loop_after' ); }	

function colabs_comment_before() { colabs_do_atomic( 'colabs_comment_before' ); }
function colabs_comment_after() { colabs_do_atomic( 'colabs_comment_after' ); }

// Tumblog Functionality
function colabs_tumblog_content_before() { colabs_do_atomic( 'colabs_tumblog_content_before', 'Before' ); }	
function colabs_tumblog_content_after() { colabs_do_atomic( 'colabs_tumblog_content_after', 'After' ); }

// Sidebar
function colabs_sidebar_before() { colabs_do_atomic( 'colabs_sidebar_before' ); }					
function colabs_sidebar_inside_before() { colabs_do_atomic( 'colabs_sidebar_inside_before' ); }					
function colabs_sidebar_inside_after() { colabs_do_atomic( 'colabs_sidebar_inside_after' ); }					
function colabs_sidebar_after() { colabs_do_atomic( 'colabs_sidebar_after' ); }					

// footer.php
function colabs_footer_top() { colabs_do_atomic( 'colabs_footer_top' ); }					
function colabs_footer_before() { colabs_do_atomic( 'colabs_footer_before' ); }					
function colabs_footer_inside() { colabs_do_atomic( 'colabs_footer_inside' ); }					
function colabs_footer_after() { colabs_do_atomic( 'colabs_footer_after' ); }	
function colabs_foot() { colabs_do_atomic( 'colabs_foot' ); }					

/*-----------------------------------------------------------------------------------*/
/* Contextual Hook and Filter Functions */
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* colabs_do_atomic() */
/*-----------------------------------------------------------------------------------*/
/**
 * Adds contextual action hooks to the theme.  This allows users to easily add context-based content 
 * without having to know how to use WordPress conditional tags.  The theme handles the logic.
 *
 * An example of a basic hook would be 'colabs_head'.  The colabs_do_atomic() function extends that to 
 * give extra hooks such as 'colabs_head_home', 'colabs_head_singular', and 'colabs_head_singular-page'.
 *
 * Major props to Ptah Dunbar for the do_atomic() function.
 * @link http://ptahdunbar.com/wordpress/smarter-hooks-context-sensitive-hooks
 *
 * @since 3.9.0
 * @uses colabs_get_query_context() Gets the context of the current page.
 * @param string $tag Usually the location of the hook but defines what the base hook is.
 */

if ( ! function_exists( 'colabs_do_atomic' ) ) {
	function colabs_do_atomic( $tag = '', $args = '' ) {
		
		if ( !$tag ) { return false; } // End IF Statement
	
		/* Do actions on the basic hook. */
		do_action( $tag, $args );
	
		/* Loop through context array and fire actions on a contextual scale. */
		foreach ( (array) colabs_get_query_context() as $context ) {
		
			do_action( "{$tag}_{$context}", $args );
			
		} // End FOREACH Loop
			
	} // End colabs_do_atomic()
} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* colabs_apply_atomic() */
/*-----------------------------------------------------------------------------------*/
/**
 * Adds contextual filter hooks to the theme.  This allows users to easily filter context-based content 
 * without having to know how to use WordPress conditional tags. The theme handles the logic.
 *
 * An example of a basic hook would be 'colabs_entry_meta'.  The colabs_apply_atomic() function extends 
 * that to give extra hooks such as 'colabs_entry_meta_home', 'colabs_entry_meta_singular' and 'colabs_entry_meta_singular-page'.
 *
 * @since 3.9.0
 * @uses colabs_get_query_context() Gets the context of the current page.
 * @param string $tag Usually the location of the hook but defines what the base hook is.
 * @param mixed $value The value to be filtered.
 * @return mixed $value The value after it has been filtered.
 */

if ( ! function_exists( 'colabs_apply_atomic' ) ) {
	function colabs_apply_atomic( $tag = '', $value = '' ) {
	
		if ( !$tag )
			return false;
	
		/* Get theme prefix. */
		// $pre = colabs_get_prefix();
		$pre = 'colabs';
		
		/* Apply filters on the basic hook. */
		$value = apply_filters( "{$pre}_{$tag}", $value );
	
		/* Loop through context array and apply filters on a contextual scale. */
		foreach ( (array)colabs_get_query_context() as $context )
			$value = apply_filters( "{$pre}_{$context}_{$tag}", $value );
	
		/* Return the final value once all filters have been applied. */
		return $value;
		
	} // End colabs_apply_atomic()
} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* colabs_get_query_context() */
/*-----------------------------------------------------------------------------------*/
/**
 * Retrieve the context of the queried template.
 *
 * @since 3.9.0
 * @return array $query_context
 */

if ( ! function_exists( 'colabs_get_query_context' ) ) {
	function colabs_get_query_context() {
		global $wp_query, $query_context;
		
		/* If $query_context->context has been set, don't run through the conditionals again. Just return the variable. */
		if ( isset( $query_context->context ) && is_array( $query_context->context ) ) {
		
			return $query_context->context;
		
		} // End IF Statement
		
		$query_context->context = array();
	
		/* Front page of the site. */
		if ( is_front_page() ) {
		
			$query_context->context[] = 'home';
			
		} // End IF Statement
	
		/* Blog page. */
		if ( is_home() && ! is_front_page() ) {
		
			$query_context->context[] = 'blog';
	
		/* Singular views. */
		} elseif ( is_singular() ) {
		
			$query_context->context[] = 'singular';
			$query_context->context[] = "singular-{$wp_query->post->post_type}";
		
			/* Page Templates. */
			if ( is_page_template() ) {
			
				$to_skip = array( 'page', 'post' );
			
				$page_template = basename( get_page_template() );
				$page_template = str_replace( '.php', '', $page_template );
				$page_template = str_replace( '.', '-', $page_template );
			
				if ( $page_template && ! in_array( $page_template, $to_skip ) ) {
			
					$query_context->context[] = $page_template;
					
				} // End IF Statement
				
			} // End IF Statement
			
			$query_context->context[] = "singular-{$wp_query->post->post_type}-{$wp_query->post->ID}";
		}
	
		/* Archive views. */
		elseif ( is_archive() ) {
			$query_context->context[] = 'archive';
	
			/* Taxonomy archives. */
			if ( is_tax() || is_category() || is_tag() ) {
				$term = $wp_query->get_queried_object();
				$query_context->context[] = 'taxonomy';
				$query_context->context[] = $term->taxonomy;
				$query_context->context[] = "{$term->taxonomy}-" . sanitize_html_class( $term->slug, $term->term_id );
			}
	
			/* User/author archives. */
			elseif ( is_author() ) {
				$query_context->context[] = 'user';
				$query_context->context[] = 'user-' . sanitize_html_class( get_the_author_meta( 'user_nicename', get_query_var( 'author' ) ), $wp_query->get_queried_object_id() );
			}
	
			/* Time/Date archives. */
			else {
				if ( is_date() ) {
					$query_context->context[] = 'date';
					if ( is_year() )
						$query_context->context[] = 'year';
					if ( is_month() )
						$query_context->context[] = 'month';
					if ( get_query_var( 'w' ) )
						$query_context->context[] = 'week';
					if ( is_day() )
						$query_context->context[] = 'day';
				}
				if ( is_time() ) {
					$query_context->context[] = 'time';
					if ( get_query_var( 'hour' ) )
						$query_context->context[] = 'hour';
					if ( get_query_var( 'minute' ) )
						$query_context->context[] = 'minute';
				}
			}
		}
	
		/* Search results. */
		elseif ( is_search() ) {
			$query_context->context[] = 'search';
	
		/* Error 404 pages. */
		} elseif ( is_404() ) {
			$query_context->context[] = 'error-404';
	
		} // End IF Statement
		
		return $query_context->context;
	
	} // End colabs_get_query_context()
} // End IF Statement
?>