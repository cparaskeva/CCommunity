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
class BP_Tools_Facilities_Component extends BP_Component {

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
                'tools_facilities', __('Tools_Facilities', 'cecom-tools_facilities'), BP_TOOLS_FACILITIES_PLUGIN_DIR
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
            'includes/cecom-tools_facilities-screens.php',
            'includes/cecom-tools_facilities-filters.php',
            'includes/cecom-tools_facilities-classes.php',
            'includes/cecom-tools_facilities-activity.php',
            'includes/cecom-tools_facilities-template.php',
            'includes/cecom-tools_facilities-functions.php',
            'includes/cecom-tools_facilities-notifications.php',
            'includes/cecom-tools_facilities-cssjs.php',
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
     * @package CECommunity Tools_Facilities Component
     *
     * @global obj $bp BuddyPress's global object
     */
    function setup_globals() {
        global $bp;
        // Defining the slug in this way makes it possible for site admins to override it
        if (!defined('BP_TOOLS_FACILITIES_SLUG'))
            define('BP_TOOLS_FACILITIES_SLUG', $this->id);

        // Global tables for the example component. Build your table names using
        // $bp->table_prefix (instead of hardcoding 'wp_') to ensure that your component
        // works with $wpdb, multisite, and custom table prefixes.
        $global_tables = array(
            'table_name' => 'ext_tool_facility' //$bp->table_prefix . 'bp_tools_facilities'
        );

        // Set up the $globals array to be passed along to parent::setup_globals()
        $globals = array(
            'slug' => BP_TOOLS_FACILITIES_SLUG,
            'root_slug' => isset($bp->pages->{$this->id}->slug) ? $bp->pages->{$this->id}->slug : BP_TOOLS_FACILITIES_SLUG,
            'has_directory' => true, // Set to false if not required
            'notification_callback' => 'bp_tools_facilities_format_notifications',
            'search_string' => __('Search Tools & Facilities...', 'buddypress'),
            'global_tables' => $global_tables
        );

        // Let BP_Component::setup_globals() do its work.
        parent::setup_globals($globals);

        //Set the subdomain for each tool_facility e.g. /cecommunity/tools_facilities/{subdomain}{tool_facilityID}
        $bp->{$this->id}->tools_facilities_subdomain = 'tool_facility';

        /** Single Tool_Facility Globals ********************************************* */
        // Are we viewing a single tool_facility?
        if (bp_is_tool_facility_component() && $tool_facility_id = BP_Tool_Facility::tool_facility_exists(bp_current_action())) {
            $bp->is_single_item = true;
            $current_tool_facility_class = apply_filters('bp_tools_facilities_current_tool_facility_class', 'BP_Tool_Facility');
            $this->current_tool_facility = apply_filters('bp_tools_facilities_current_tool_facility_object', new $current_tool_facility_class(Array('id' => $tool_facility_id)));
            $this->current_tool_facility->slug = $bp->{$this->id}->tools_facilities_subdomain . $tool_facility_id;

            // When in a single tool_facility, the first action is bumped down one because of the
            // tool_facility name, so we need to adjust this and set the group name to current_item.
            $bp->current_item = bp_current_action();
            $bp->current_action = bp_action_variable(0);
            array_shift($bp->action_variables);

            //Set if the user is owner of the tool_facility
            bp_update_is_item_admin(bp_loggedin_user_id() == $this->current_tool_facility->uid, 'tools_facilities');

            //echo "Current Action: ".$bp->current_action." Tool_FacilityID: ".$tool_facility_id. "   ". $bp->is_single_item. "Single item? ". bp_is_single_item() ; //die();
        } else {
            $this->current_tool_facility = 0;
        }
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
        // Add 'Tools_Facilities' to the main navigation
        $main_nav = array(
            'name' => sprintf(__('Tools & Facilities  <span>%s</span>', 'buddypress'), bp_get_total_tools_facilities_count_for_user()),
            'slug' => bp_get_tools_facilities_slug(),
            'position' => 80,
            'screen_function' => 'bp_tools_facilities_screen_one',
            'default_subnav_slug' => 'screen-one'
        );

        $tool_facility_link = trailingslashit(bp_loggedin_user_domain() . bp_get_tools_facilities_slug());

        // Add a few subnav items under the main Example tab
        $sub_nav[] = array(
            'name' => __('Published Tools/Facilities', 'cecom-tools_facilities'),
            'slug' => 'screen-one',
            'parent_url' => $tool_facility_link,
            'parent_slug' => bp_get_tools_facilities_slug(),
            'screen_function' => 'bp_tools_facilities_screen_one',
            'position' => 10
        );

        // Add the subnav items to the friends nav item
        $sub_nav[] = array(
            'name' => __('Create a tool/facility rent', 'cecom-tools_facilities'),
            'slug' => 'create-tool_facility',
            'parent_url' => $tool_facility_link,
            'parent_slug' => bp_get_tools_facilities_slug(),
            'screen_function' => 'bp_tools_facilities_create_tool_facility',
            'position' => 20
        );

        
        parent::setup_nav($main_nav, $sub_nav);


        if (bp_is_tool_facility_component() && bp_is_single_item()) {

            // Reset sub nav
            $sub_nav = array();

            // Add 'Groups' to the main navigation
            $main_nav = array(
                'name' => __('Home', 'buddypress'),
                'slug' => $this->current_tool_facility->slug,
                'position' => -1, // Do not show in BuddyBar
                'screen_function' => 'tools_facilities_screen_tool_facility_home',
                'default_subnav_slug' => $this->default_extension,
                'item_css_id' => $this->id
            );

            $tool_facility_link = trailingslashit(bp_get_root_domain() . '/' . bp_get_tools_facilities_root_slug() . '/' . $this->current_tool_facility->slug . '/');

            // If the user is tool_facility ownner, then show the tool_facility admin nav item
            if (bp_is_item_admin()) {
                global $bp;
                $sub_nav[] = array(
                    'name' => __('Admin', 'buddypress'),
                    'slug' => 'admin',
                    'parent_url' => $tool_facility_link,
                    'parent_slug' => $this->current_tool_facility->slug,
                    'screen_function' => 'tools_facilities_screen_tool_facility_admin',
                    'position' => 40,
                    'user_has_access' => true,
                    'item_css_id' => 'admin'
                );
            }



            parent::setup_nav($main_nav, $sub_nav);
        }

        if (isset($this->current_tool_facility->user_has_access)) {
            do_action('tools_facilities_setup_nav', $this->current_tool_facility->user_has_access);
        } else {
            do_action('tools_facilities_setup_nav');
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
 *   function bp_tools_facilities_var_dump() {
 *   	  global $bp;
 * 	  var_dump( $bp->example );
 *   }
 *   add_action( 'bp_init', 'bp_tools_facilities_var_dump' );
 * It goes without saying that you should not do this on a production site!
 *
 * @package Tools_Facilities Component
 */
function bp_tools_facilities_load_core_component() {
    global $bp;

    $bp->tools_facilities = new BP_Tools_Facilities_Component();
}

add_action('bp_loaded', 'bp_tools_facilities_load_core_component', 1);
?>