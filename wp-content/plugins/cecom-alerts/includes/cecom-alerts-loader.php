<?php

// Exit if accessed directly
// It's a good idea to include this in each of your plugin files, for increased security on
// improperly configured servers
if (!defined('ABSPATH'))
    exit;

/**
 * Implementation of BP_Component
 *
 * BP_Component is the base class that all BuddyPress components use to set up their basic
 * structure, including global data, navigation elements, and admin bar information. If there's
 * a particular aspect of this class that is not relevant to your plugin, just leave it out.
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
class BP_Alerts_Component extends BP_Component {

    /**
     * Constructor method
     *
     * You can do all sorts of stuff in your constructor, but it's recommended that, at the
     * very least, you call the parent::start() function. This tells the parent BP_Component
     * to begin its setup routine.
     *
     * BP_Component::start() takes three parameters:
     *   (1) $id   - A unique identifier for the component. Letters, numbers, and underscores
     * 		 only.
     *   (2) $name - This is a translatable name for your component, which will be used in
     *               various places through the BuddyPress admin screens to identify it.
     *   (3) $path - The path to your plugin directory. Primarily, this is used by
     * 		 BP_Component::includes(), to include your plugin's files. See loader.php
     * 		 to see how BP_EXAMPLE_PLUGIN_DIR was defined.
     */
    function __construct() {
        global $bp;

        parent::start(
                'alerts', __('Alerts', 'cecom-alerts'), BP_ALERTS_PLUGIN_DIR
        );

        /**
         * BuddyPress-dependent plugins are loaded too late to depend on BP_Component's
         * hooks, so we must call the function directly.
         */
        $this->includes();

        /**
         * Put your component into the active components array, so that
         *   bp_is_active( 'example' );
         * returns true when appropriate. We have to do this manually, because non-core
         * components are not saved as active components in the database.
         */
        $bp->active_components[$this->id] = '1';
    }

    function includes() {

        // Files to include
        $includes = array(
            'includes/cecom-alerts-screens.php',
            'includes/cecom-alerts-filters.php',
            'includes/cecom-alerts-classes.php',
            'includes/cecom-alerts-activity.php',
            'includes/cecom-alerts-template.php',
            'includes/cecom-alerts-functions.php',
            'includes/cecom-alerts-notifications.php',
        );

        parent::includes($includes);
    }

    /**
     * Set up your plugin's globals
     *
     * Use the parent::setup_globals() method to set up the key global data for your plugin:
     *   - 'slug'			- This is the string used to create URLs when your component
     * 				  adds navigation underneath profile URLs. For example,
     * 				  in the URL http://testbp.com/members/boone/example, the
     * 				  'example' portion of the URL is formed by the 'slug'.
     * 				  Site admins can customize this value by defining
     * 				  BP_EXAMPLE_SLUG in their wp-config.php or bp-custom.php
     * 				  files.
     *   - 'root_slug'		- This is the string used to create URLs when your component
     * 				  adds navigation to the root of the site. In other words,
     * 				  you only need to define root_slug if your component is a
     * 				  "root component". Eg, in:
     * 				    http://testbp.com/example/test
     * 				  'example' is a root slug. This should always be defined
     * 				  in terms of $bp->pages; see the example below. Site admins
     * 				  can customize this value by changing the permalink of the
     * 				  corresponding WP page in the Dashboard. NOTE:
     * 				  'root_slug' requires that 'has_directory' is true.
     *   - 'has_directory'		- Set this to true if your component requires a top-level
     * 				  directory, such as http://testbp.com/example. When
     * 				  'has_directory' is true, BP will require that site admins
     * 				  associate a WordPress page with your component. NOTE:
     * 				  When 'has_directory' is true, you must also define your
     * 				  component's 'root_slug'; see previous item. Defaults to
     * 				  false.
     *   - 'notification_callback'  - The name of the function that is used to format BP
     * 				  admin bar notifications for your component.
     *   - 'search_string'		- If your component is a root component (has_directory),
     * 				  you can provide a custom string that will be used as the
     * 				  default text in the directory search box.
     *   - 'global_tables'		- If your component creates custom database tables, store
     * 				  the names of the tables in a $global_tables array, so that
     * 				  they are available to other BP functions.
     *
     * You can also use this function to put data directly into the $bp global.
     *
     * @package CECommunity Alerts Component
     *
     * @global obj $bp BuddyPress's global object
     */
    function setup_globals() {
        global $bp;
        // Defining the slug in this way makes it possible for site admins to override it
        if (!defined('BP_ALERTS_SLUG'))
            define('BP_ALERTS_SLUG', $this->id);

        // Global tables for the example component. Build your table names using
        // $bp->table_prefix (instead of hardcoding 'wp_') to ensure that your component
        // works with $wpdb, multisite, and custom table prefixes.
        $global_tables = array(
            'table_name' => 'ext_alert' //$bp->table_prefix . 'bp_alerts'
        );

        // Set up the $globals array to be passed along to parent::setup_globals()
        $globals = array(
            'slug' => BP_ALERTS_SLUG,
            'root_slug' => isset($bp->pages->{$this->id}->slug) ? $bp->pages->{$this->id}->slug : BP_ALERTS_SLUG,
            'has_directory' => true, // Set to false if not required
            'notification_callback' => 'bp_alerts_format_notifications',
            'search_string' => __('Search Alerts ...', 'buddypress'),
            'global_tables' => $global_tables
        );

        // Let BP_Component::setup_globals() do its work.
        parent::setup_globals($globals);

        //Set the subdomain for each alert e.g. /cecommunity/alerts/{subdomain}{alertID}
        $bp->{$this->id}->alerts_subdomain = 'alert';
        $this->current_alert = 0;
    }

    /**
     * Set up your component's navigation.
     *
     * The navigation elements created here are responsible for the main site navigation (eg
     * Profile > Activity > Mentions), as well as the navigation in the BuddyBar. WP Admin Bar
     * navigation is broken out into a separate method; see
     * BP_Example_Component::setup_admin_bar().
     *
     * @global obj $bp
     */
    function setup_nav() {
        // Add 'Alerts' to the main navigation
        $main_nav = array(
            'name' => sprintf(__('Alerts  <span>%s</span>', 'buddypress'), bp_get_total_alerts_count_for_user()),
            'slug' => bp_get_alerts_slug(),
            'position' => 80,
            'screen_function' => 'bp_alerts_screen_one',
            'default_subnav_slug' => 'screen-one'
        );

        $alert_link = trailingslashit(bp_loggedin_user_domain() . bp_get_alerts_slug());

        // Add a few subnav items under the main Example tab
        $sub_nav[] = array(
            'name' => __('Current Alerts', 'cecom-alerts'),
            'slug' => 'screen-one',
            'parent_url' => $alert_link,
            'parent_slug' => bp_get_alerts_slug(),
            'screen_function' => 'bp_alerts_screen_one',
            'position' => 10
        );


        parent::setup_nav($main_nav, $sub_nav);


        if (isset($this->current_alert->user_has_access)) {
            do_action('alerts_setup_nav', $this->current_alert->user_has_access);
        } else {
            do_action('alerts_setup_nav');
        }
    }

}

/**
 * Loads your component into the $bp global
 *
 * This function loads your component into the $bp global. By hooking to bp_loaded, we ensure that
 * BP_Example_Component is loaded after BuddyPress's core components. This is a good thing because
 * it gives us access to those components' functions and data, should our component interact with
 * them.
 *
 * Keep in mind that, when this function is launched, your component has only started its setup
 * routine. Using print_r( $bp->example ) or var_dump( $bp->example ) at the end of this function
 * will therefore only give you a partial picture of your component. If you need to dump the content
 * of your component for troubleshooting, try doing it at bp_init, ie
 *   function bp_alerts_var_dump() {
 *   	  global $bp;
 * 	  var_dump( $bp->example );
 *   }
 *   add_action( 'bp_init', 'bp_alerts_var_dump' );
 * It goes without saying that you should not do this on a production site!
 *
 * @package Alerts Component
 */
function bp_alerts_load_core_component() {
    global $bp;

    $bp->alerts = new BP_Alerts_Component();
}

add_action('bp_loaded', 'bp_alerts_load_core_component', 1);
?>