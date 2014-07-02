<?php

//Set true, enabling debug mode
if (!defined('DEBUG'))
    define('DEBUG', true);

define('CECOM_DISABLE_ADMIN_BAR', true);


/* DO NOT MODIFY THE FOLLOWING FUNCTIONS
 * 
 *  *********  SECURITY RISK
 * 
 * USED ONLY BY CECOMMUNITY PLATFORM
 */

function cecom_is_front_page() {
    return ($_SERVER['REQUEST_URI'] == "/cecommunity/");
}

function cecom_is_register_page() {
    return rtrim($_SERVER['REQUEST_URI'], "/") == "/cecommunity/register";
}

function cecom_is_activation_page() {
    preg_match('/(\/[a-z]*\/[a-z]*\/).*$/i', $_SERVER['REQUEST_URI'], $matches);
    return ($matches[1] == "/cecommunity/activate/");

    //return (rtrim($_SERVER['REQUEST_URI'], "/") == "/cecommunity/activate");
}

function cecom_is_login_page() {
    preg_match('/(\/[a-z]*\/[a-z]*-[a-z]*).*$/i', $_SERVER['REQUEST_URI'], $matches);
    return ( $matches[1] == "/cecommunity/wp-login" );
}

function cecom_is_wp_admin_page() {
    preg_match('/(\/[a-z]*\/[a-z]*-[a-z]*\/).*$/i', $_SERVER['REQUEST_URI'], $matches);
    return ($matches[1] == "/cecommunity/wp-admin/");
}

function cecom_is_wp_cron_page() {
    preg_match('/(\/[a-z]*\/[a-z]*-[a-z]*).*$/i', $_SERVER['REQUEST_URI'], $matches);
    return ($matches[1] == "/cecommunity/wp-cron");
}

//Define all core pages that are accessed without logged-in precondition
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
if (cecom_is_wp_cron_page())
    define('WP_CRON_PAGE', true);



/* Prevent an un-authorized user to have access to the platform */
if (!is_user_logged_in() && !defined('REGISTER_PAGE') && !defined('ACTIVATION_PAGE') && !defined('LOGIN_PAGE') && !defined('FRONT_PAGE') && !defined('WP_ADMIN_PAGE') && !defined('WP_CRON_PAGE')) {
    //Redirect to home page...    
    wp_redirect(home_url());
    exit;
}
/* CORE FUNCTIONS END */

/**
 * Enqueue stylesheets(CSS) & javascripts(JS)
 */
function custom_javascripts() {

    //Javascript Help Functions
    wp_register_script('cecommunity-functions', get_stylesheet_directory_uri() . '/assets/js//cecommunity-functions.js');
    wp_enqueue_script('cecommunity-functions');

    //Bootstrapformhelpers JS library
    wp_register_script('bootstrapformhelpers', get_stylesheet_directory_uri() . '/assets/bootstrapformhelpers/js/bootstrap-formhelpers.js');

    //Bootstrap multi-select
    wp_register_script('bootstrap-multiselect', get_stylesheet_directory_uri() . '/assets/js/bootstrap-multiselect.js');

    //Bootstrapformhelpers CSS
    wp_register_style('bootstrapformhelpers-style', get_stylesheet_directory_uri() . "/assets/bootstrapformhelpers/css/bootstrap-formhelpers.css");

    //Bootstrap multi-select CSS
    wp_register_style('bootstrap-multiselect-style', get_stylesheet_directory_uri() . '/assets/css/bootstrap-multiselect.css');
}

add_action('wp_enqueue_scripts', 'custom_javascripts');

//Implementation of AJAX Calls need for the registration process of a user
require(get_stylesheet_directory() . "/registration/register_functions.php");


add_action("firmasite_settings_close", "firmasite_custom_container_size");

//Customize sidebar menu
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


/* Debug purposes functions .... */

function bpdev_redirect_to_profile($redirect_to_calculated, $redirect_url_specified, $user) {


    if (empty($redirect_to_calculated))
        $redirect_to_calculated = admin_url();

    /* if the user is not site admin,redirect to his/her profile */

    if (!is_site_admin($user->user_login))
        return bp_core_get_user_domain($user->ID);
    else
        return $redirect_to_calculated; /* if site admin or not logged in,do not do anything much */
}

//If debug mechanism is activated do the following:
if (defined('DEBUG') && DEBUG) {
    $wpdb->show_errors();

    //Redirect User to specific site based on the roles	
    add_filter("login_redirect", "bpdev_redirect_to_profile", 10, 3);
}

//Disable the admin bar but still show it if the user is a wordpress admin
if (defined('CECOM_DISABLE_ADMIN_BAR') && CECOM_DISABLE_ADMIN_BAR == true && !current_user_can('manage_options'))
    show_admin_bar(false);

//Define all alerts fingertips -- Used only within BP_Alert_Factory CLASS:
define("SEARCH_ORGANIZATION_ARGS", md5("organization-sectors;|organization-subsectors;|organization-collaboration;|organization-transaction;|organization-size;none|organization-type;none|collaboration-description;|collaboration-type;none|collaboration-partner-sought;none|collaboration-programs;none|offer-type;none|organization-country;|text;"));
define("SEARCH_ORGANIZATION_READY_TO_COLLABORATE_DEVELOP_ARGS", md5("organization-sectors;|organization-subsectors;|organization-collaboration;|organization-transaction;|organization-size;none|organization-type;none|collaboration-description;|collaboration-type;none|collaboration-partner-sought;none|collaboration-programs;none|offer-type;1|organization-country;|text;"));
define("SEARCH_ORGANIZATION_READY_TO_COLLABORATE_FUNDING_ARGS", md5("organization-sectors;|organization-subsectors;|organization-collaboration;|organization-transaction;|organization-size;none|organization-type;none|collaboration-description;|collaboration-type;none|collaboration-partner-sought;none|collaboration-programs;none|offer-type;2|organization-country;|text;"));
define("SEARCH_OFFER_PATENT_LICENSE_ARGS", md5("patent-license-sectors;|patent-license-subsectors;|patent-license-type;none|patent-license-exchange;none|patent-license-countries;none|organization-name;|organization-type;none|organization-country;|text;"));
define("SEARCH_OFFER_FUNDING_ARGS", md5("offer-sectors;|offer-type;3|collaboration-type;none|collaboration-partner-sought;none|collaboration-programs;none|applyable-countries;none|finance-stage;none|text;"));




/* Used for: Alerts Component Used by: WP Cron Tiggered by: Crond(UNIX/LINUX)
 * Function used to add custom time for CRON scheduled jobs
 */

function cron_add_alert_system_time($schedules) {

    // Adds once weekly to the existing schedules.
    $schedules['alert_system'] = array(
        'interval' => 60,
        'display' => __('Alert System Time')
    );
    return $schedules;
}
add_filter('cron_schedules', 'cron_add_alert_system_time');



//Set default buddypress navigation bar "Profile"
define( "BP_DEFAULT_COMPONENT", "profile" );


//Remove activity menu
function bp_remove_nav_tabs() {
bp_core_remove_nav_item( "activity" );

}
add_action( "bp_setup_nav", "bp_remove_nav_tabs", 15 );




?>