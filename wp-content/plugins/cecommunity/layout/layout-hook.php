<?php 

/**
 * 
 */


function hook_head()
{
	//$output="<style> .wp_head_example { background-color : #f1f1f1; } </style>";

	// first, we call "https://fonts.googleapis.com" (with "https" instead of "http") to load our font (chrome issue, cf. https://code.google.com/p/chromium/issues/detail?id=336476#c40)
	$output = "<link rel='stylesheet' id='google-webfonts-css'  href='http://fonts.googleapis.com/css?family=Tenor+Sans' type='text/css' media='all' />"; 
	///<link rel='stylesheet' id='google-webfonts-css'  href='https://fonts.googleapis.com/css?family=Ubuntu+Condensed%3A300%2C400%2C700%7C&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />\n";
	
	// then, this other hack, still cf. https://code.google.com/p/chromium/issues/detail?id=336476#c40
	$output .= "<style>body {
	    -webkit-animation-delay: 0.1s;
	    -webkit-animation-name: fontfix;
	    -webkit-animation-duration: 0.1s;
	    -webkit-animation-iteration-count: 1;
	    -webkit-animation-timing-function: linear;
	}
	
	@-webkit-keyframes fontfix {
	    from { opacity: 1; }
	    to   { opacity: 1; }
	}</style>\n";
	
		
	$output .= '<style>';
	/* /cecommunity/groups/ : no "active 7 hours, 1 minute ago", no "​Request membership", 
	 * no "​Private Organisation / x member", no ​"This is a private group and you...", no "​Create a group", 
	 * no title "Organisations Directory ", no "All organisations" */
	$output .= "body.groups span.activity,  body.groups span.highlight, body.groups #groups-dir-list div.action, body.groups h3#offers-header, body.groups div.item-list-tabs { display:none }\n";
	
	
	$output .= '</style>';
	
	
	// finally, we remove wordpress logo
	///$output .= "<script>jQuery( document ).ready( function() { jQuery('#logo').remove();  }); </script>\n";
	
	
	
	
	echo $output;

}

add_action('wp_head','hook_head');


/// http://webdesignfromscratch.com/wordpress/using-google-web-fonts-with-wordpress-the-right-way/
/*
function load_fonts() {
	wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Rock+Salt|Neucha');
	wp_enqueue_style( 'googleFonts');
}

add_action('wp_print_styles', 'load_fonts');
*/