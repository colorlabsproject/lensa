jQuery(document).ready(function() {	
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			jQuery( '.branding h1 a' ).html( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			jQuery( '.branding .site-description' ).html( to );
		} );
	} );

});