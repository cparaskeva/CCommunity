<?php   
      add_action("firmasite_settings_close", "firmasite_custom_container_size");
    function firmasite_custom_container_size(){
    global $firmasite_settings;
    switch ($firmasite_settings["layout"]) {
    case "sidebar-content":
    $firmasite_settings["layout_primary_class"] = "col-xs-12 col-md-9 pull-right";
    $firmasite_settings["layout_secondary_class"] = "col-xs-12 col-md-3";	
    break;
    case "content-sidebar":
    $firmasite_settings["layout_primary_class"] = "col-xs-12 col-md-9";
    $firmasite_settings["layout_secondary_class"] = "col-xs-12 col-md-3";	
    break;	
    }
    }
    
    
    // If you define this as false, designer info in bottom will not show
if ( !defined('FIRMASITE_DESIGNER') )
	define('FIRMASITE_DESIGNER', false);

	
if ( !defined('FIRMASITE_CDN') )
	define('FIRMASITE_CDN', false);
