<?php

/* DO NOT MODIFY THE FOLLOWING FUNCTIONS
 * 
 *  *********  SECURITY RISK
 * 
 * USED ONLY BY CECOMMUNITY PLATFORM
 */

function cecom_is_front_page() {
    preg_match('/\/[a-z]*\/([a-z]*)$/i', home_url(), $matches);
    print_r($matches);
    return (trim($_SERVER['REQUEST_URI'], "/") == $matches[1]);
}

function cecom_is_register_page() {
    return $_SERVER['REQUEST_URI'] == "/cecommunity/register/";
}

function cecom_is_activation_page() {
    return (trim($_SERVER['REQUEST_URI']) == "/cecommunity/activate/");
}

function cecom_is_login_page() {   
    preg_match('/(\/[a-z]*\/[a-z]*-[a-z]*).*$/i', $_SERVER['REQUEST_URI'], $matches);
    print_r($matches);
    return (  $matches[1] == "/cecommunity/wp-login" );
}

function cecom_is_wp_admin_page() {
    preg_match('/(\/[a-z]*\/[a-z]*-[a-z]*\/).*$/i', $_SERVER['REQUEST_URI'], $matches);
    print_r($matches);
    return ($matches[1] == "/cecommunity/wp-admin/");
}


//Define all core pages that are accessed withou logged-in precondition
if (cecom_is_register_page())
    define('REGISTER_PAGE', true);
if (cecom_is_activation_page())
    define('ACTIVATION_PAGE', true);
if (cecom_is_login_page())
    define('LOGIN_PAGE', true);
if (cecom_is_front_page())
    define('FRONT_PAGE', true);
if (cecom_is_wp_admin_page())
    define('WP_ADMIN_PAGE', true);



/* Prevent an un-authorized user to have access to the platform */
if (!is_user_logged_in() && !defined('REGISTER_PAGE') && !defined('ACTIVATION_PAGE') && !defined('LOGIN_PAGE') && !defined('FRONT_PAGE') &&  !defined('WP_ADMIN_PAGE') ) {
    //Redirect to home page...
   
    echo "<br><br>".$_SERVER['REQUEST_URI']."<br><br>";
    
    //wp_redirect(home_url());
    //exit;
    echo home_url();
    die();
}
/* CORE FUNCTIONS END */



/**
 * Enqueue stylesheets(CSS) & javascripts(JS)
 */
function custom_java_scripts() {
    wp_register_script('cecommunity-functions', get_stylesheet_directory_uri() . '/assets/js//cecommunity-functions.js');
    wp_enqueue_script("cecommunity-functions");
    wp_register_script('bootstrap-formhelpers', get_template_directory() . "/assets/bootstrapformhelpers/css/bootstrap-formhelpers.css");
    wp_enqueue_script('bootstrap-formhelpers');
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

function bpdev_redirect_to_profile($redirect_to_calculated, $redirect_url_specified, $user) {


    if (empty($redirect_to_calculated))
        $redirect_to_calculated = admin_url();

    /* if the user is not site admin,redirect to his/her profile */

    if (!is_site_admin($user->user_login))
        return bp_core_get_user_domain($user->ID);
    else
        return $redirect_to_calculated; /* if site admin or not logged in,do not do anything much */
}

//Redirect User to specific site based on the roles	
add_filter("login_redirect", "bpdev_redirect_to_profile", 10, 3);
