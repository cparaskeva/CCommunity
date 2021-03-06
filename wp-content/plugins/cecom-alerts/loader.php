<?php

/*
  Plugin Name: CECommunity Alerts
  Plugin URI: http://
  Description: This component is used alert the user when specific events happend to the platform.
  Version: 1.0
  Revision Date: MMMM DD, YYYY
  Requires at least: BuddyPress version > 1.8
  Author: Chris Paraskeva
  Network: true
 */

// Define a constant that can be checked to see if the component is installed or not.
define('BP_ALERTS_IS_INSTALLED', 1);

// Define a constant that will hold the current version number of the component
// This can be useful if you need to run update scripts or do compatibility checks in the future
define('BP_ALERTS_VERSION', '1.0');

// Define a constant that we can use to construct file paths throughout the component
define('BP_ALERTS_PLUGIN_DIR', dirname(__FILE__));

/* Only load the component if BuddyPress is loaded and initialized. */

function bp_alerts_init() {
    // Because our loader file uses BP_Component, it requires BP 1.5 or greater.
    if (version_compare(BP_VERSION, '1.3', '>'))
        require( dirname(__FILE__) . '/includes/cecom-alerts-loader.php' );
}

add_action('bp_include', 'bp_alerts_init');


register_activation_hook(__FILE__, 'bp_alerts_activate');


/* Put setup procedures to be run when the plugin is activated in the following function */

function bp_alerts_activate() {
    wp_schedule_event(time(), 'alert_system', 'cecom_alerts');
}

/* On deacativation, clean up anything your component has added. */

function bp_alerts_deactivate() {
    wp_clear_scheduled_hook('cecom_alerts');
    /* You might want to delete any options or tables that your component created. */
}

register_deactivation_hook(__FILE__, 'bp_alerts_deactivate');

add_action('cecom_alerts', 'cecom_tigger_alert_system');

//Function which is called by the CRON scheduler
function cecom_tigger_alert_system() {
    if ($_GET['debug']) {
        $time_start = microtime(true); // time in Microseconds
        echo("<h4>Alert System started at: ") . date('Y-m-d H:i:s', $time_start . "</h4><hr><br><br>");
    }
    BP_Alert_Factory::run_alert_system();
    if ($_GET['debug'])
        echo ("<br><h4>Alert System ended at: " . date('Y-m-d H:i:s', time()) . " Total Time: " . (microtime(true) - $time_start ) . " sec</h4>");
}

?>
