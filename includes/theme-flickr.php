<?php

	define('colabs_flickr_version', '1.0');
	
	$colabsIncludePath = get_include_path().PATH_SEPARATOR.get_stylesheet_directory().'/includes/flickr/';
		
	if(!set_include_path($colabsIncludePath)) ini_set('include_path',	$colabsIncludePath);

	require_once 'phpFlickr.php';
	