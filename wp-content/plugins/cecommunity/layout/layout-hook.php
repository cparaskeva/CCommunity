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
							
	/* all search boxes left aligned + no "?" */
	$output .= "#group-dir-search {float:left !important; margin:10px 0 0 30px} #group-dir-search input[type=text] {width: 240px}   body.groups div.padder div.panel {padding-top:50px} body.groups .glyphicon-question-sign {display:none}\n".
						"#offer-dir-search {float:left !important; margin:10px 0 0 30px} #offer-dir-search  input[type=text] {width: 240px}  body.offers div.padder div.panel {padding-top:50px} body.offers .glyphicon-question-sign {display:none}\n".
						"#patent_license-dir-search {float:left !important; margin:10px 0 0 30px} #patent_license-dir-search input[type=text] {width: 240px}   body.patents_licenses div.padder div.panel {padding-top:50px} body.patents_licenses .glyphicon-question-sign {display:none}\n".
						"#tool_facility-dir-search {float:left !important; margin:10px 0 0 30px}  #tool_facility-dir-search input[type=text] {width: 240px}  body.tools_facilities div.padder div.panel {padding-top:50px} body.tools_facilities .glyphicon-question-sign {display:none}\n".
						"#groups_search_submit, #offers_search_submit, #patents_licenses_search_submit, #tools_facilities_search_submit { position: relative;top: -3px }";  
	
	/* avatars are not displayed */
	$output .= "img.avatar, div.item-avatar, div#item-header-avatar a.thumbnail {display: none}";
	
	/* offer owner + group admins + ... not displayed */
	$output .= "#item-header #item-actions strong {display: none}";
	
	/* no "2x organisation" on /cecommunity/tools_facilities/tool_facility1/ or /cecommunity/patents_licenses/patent_license26 */
	$output .= "#item-header-avatar > strong {display: none}";
	
	/* wider left box on /cecommunity/groups/organization36/ */
	$output .= "#item-header-avatar .well, body.offers #item-header-avatar .well  {width: 350px !important}";
	
	$output .= '</style>';
	
	// search buttons should be more visible + wider left column on offer detail 
	$output .= '<script>jQuery(function() { 
			jQuery("#groups_search_submit, #offers_search_submit, #patents_licenses_search_submit, #tools_facilities_search_submit").addClass("btn-primary");
			jQuery("#item-header-avatar").addClass("col-sm-4").removeClass("col-sm-2");
			});</script>';
	 	
	
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