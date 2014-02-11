<?php

/**
 * Enqueue stylesheets(CSS) & javascripts(JS)
 */
function custom_java_scripts() {
    wp_register_script('cecommunity-functions', get_stylesheet_directory_uri() . '/assets/js//cecommunity-functions.js');
    wp_enqueue_script("cecommunity-functions");
    //wp_register_script('bootstrap-formhelpers', get_template_directory() . "/assets/bootstrapformhelpers/css/bootstrap-formhelpers.css");
    //wp_enqueue_script('bootstrap-formhelpers');
}

add_action('wp_enqueue_scripts', 'custom_java_scripts');


//Implemantation of AJAX Calls need fo the registration process of a user
require(get_stylesheet_directory() . "/registration/register_functions.php");


add_action("firmasite_settings_close", "firmasite_custom_container_size");

function firmasite_custom_container_size() {
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
    //Register Form Layout
    $firmasite_settings["layout_register_class"] = "col-xs-12 col-md-9";
}

// If you define this as false, designer info in bottom will not show
if (!defined('FIRMASITE_DESIGNER'))
    define('FIRMASITE_DESIGNER', false);


if (!defined('FIRMASITE_CDN'))
    define('FIRMASITE_CDN', false);

//Redirect User to specific site based on the roles	
//add_filter("login_redirect", "bpdev_redirect_to_profile", 10, 3);

function bpdev_redirect_to_profile($redirect_to_calculated, $redirect_url_specified, $user) {

    if (empty($redirect_to_calculated))
        $redirect_to_calculated = admin_url();



    /* if the user is not site admin,redirect to his/her profile */

    if (!is_site_admin($user->user_login))
        return bp_core_get_user_domain($user->ID);
    else
        return $redirect_to_calculated; /* if site admin or not logged in,do not do anything much */
}


