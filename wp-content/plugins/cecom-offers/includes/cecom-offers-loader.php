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
class BP_Offers_Component extends BP_Component {

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
     *
     * @package BuddyPress_Skeleton_Component
     * @since 1.6
     */
    function __construct() {
        global $bp;

        parent::start(
                'offers', __('Offers', 'cecom-offers'), BP_OFFERS_PLUGIN_DIR
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

    /**
     * Include your component's files
     *
     * BP_Component has a method called includes(), which will automatically load your plugin's
     * files, as long as they are properly named and arranged. BP_Component::includes() loops
     * through the $includes array, defined below, and for each $file in the array, it tries
     * to load files in the following locations:
     *   (1) $this->path . '/' . $file - For example, if your $includes array is defined as
     *           $includes = array( 'notifications.php', 'filters.php' );
     *       BP_Component::includes() will try to load these files (assuming a typical WP
     *       setup):
     *           /wp-content/plugins/cecom-offers/notifications.php
     *           /wp-content/plugins/cecom-offers/filters.php
     *       Our includes function, listed below, uses a variation on this method, by specifying
     *       the 'includes' directory in our $includes array.
     *   (2) $this->path . '/bp-' . $this->id . '/' . $file - Assuming the same $includes array
     *       as above, BP will look for the following files:
     *           /wp-content/plugins/cecom-offers/cecom-offers/notifications.php
     *           /wp-content/plugins/cecom-offers/cecom-offers/filters.php
     *   (3) $this->path . '/bp-' . $this->id . '/' . 'bp-' . $this->id . '-' . $file . '.php' -
     *       This is the format that BuddyPress core components use to load their files. Given
     *       an $includes array like
     *           $includes = array( 'notifications', 'filters' );
     *       BP looks for files at:
     *           /wp-content/plugins/cecom-offers/cecom-offers/cecom-offers-notifications.php
     *           /wp-content/plugins/cecom-offers/cecom-offers/cecom-offers-filters.php
     *
     * If you'd prefer not to use any of these naming or organizational schemas, you are not
     * required to use parent::includes(); your own includes() method can require the files
     * manually. For example:
     *    require( $this->path . '/includes/notifications.php' );
     *    require( $this->path . '/includes/filters.php' );
     *
     * Notice that this method is called directly in $this->__construct(). While this step is
     * not necessary for BuddyPress core components, plugins are loaded later, and thus their
     * includes() method must be invoked manually.
     *
     * Our example component includes a fairly large number of files. Your component may not
     * need to have versions of all of these files. What follows is a short description of
     * what each file does; for more details, open the file itself and see its inline docs.
     *   - -actions.php       - Functions hooked to bp_actions, mainly used to catch action
     * 			    requests (save, delete, etc)
     *   - -screens.php       - Functions hooked to bp_screens. These are the screen functions
     * 			    responsible for the display of your plugin's content.
     *   - -filters.php	  - Functions that are hooked via apply_filters()
     *   - -classes.php	  - Your plugin's classes. Depending on how you organize your
     * 			    plugin, this could mean: a database query class, a custom post
     * 			    type data schema, and so forth
     *   - -activity.php      - Functions related to the BP Activity Component. This is where
     * 			    you put functions responsible for creating, deleting, and
     * 			    modifying activity items related to your component
     *   - -template.php	  - Template tags. These are functions that are called from your
     * 			    templates, or from your screen functions. If your plugin
     * 			    contains its own version of the WordPress Loop (such as
     * 			    bp_has_offers()), those functions should go in this file.
     *   - -functions.php     - Miscellaneous utility functions required by your component.
     *   - -notifications.php - Functions related to email notification, as well as the
     * 			    BuddyPress notifications that show up in the admin bar.
     *   - -widgets.php       - If your plugin includes any sidebar widgets, define them in this
     * 			    file.
     *   - -adminbar.php      - Functions related to the WordPress Admin Bar.
     *   - -cssjs.php	  - Here is where you set up and enqueue your CSS and JS.
     *   - -ajax.php	  - Functions used in the process of AJAX requests.
     *
     * @package BuddyPress_Skeleton_Component
     * @since 1.6
     */
    function includes() {

        // Files to include
        $includes = array(
            'includes/cecom-offers-screens.php',
            'includes/cecom-offers-filters.php',
            'includes/cecom-offers-classes.php',
            'includes/cecom-offers-activity.php',
            'includes/cecom-offers-template.php',
            'includes/cecom-offers-functions.php',
            'includes/cecom-offers-notifications.php',
            'includes/cecom-offers-cssjs.php',
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
     * @package CECommunity Offers Component
     *
     * @global obj $bp BuddyPress's global object
     */
    function setup_globals() {
        global $bp;
        // Defining the slug in this way makes it possible for site admins to override it
        if (!defined('BP_OFFERS_SLUG'))
            define('BP_OFFERS_SLUG', $this->id);

        // Global tables for the example component. Build your table names using
        // $bp->table_prefix (instead of hardcoding 'wp_') to ensure that your component
        // works with $wpdb, multisite, and custom table prefixes.
        $global_tables = array(
            'table_name' => 'ext_offer' //$bp->table_prefix . 'bp_offers'
        );

        // Set up the $globals array to be passed along to parent::setup_globals()
        $globals = array(
            'slug' => BP_OFFERS_SLUG,
            'root_slug' => isset($bp->pages->{$this->id}->slug) ? $bp->pages->{$this->id}->slug : BP_OFFERS_SLUG,
            'has_directory' => true, // Set to false if not required
            'notification_callback' => 'bp_offers_format_notifications',
            'search_string' => __('Search Offers...', 'buddypress'),
            'global_tables' => $global_tables
        );

        // Let BP_Component::setup_globals() do its work.
        parent::setup_globals($globals);

        //Set the subdomain for each offer e.g. /cecommunity/offers/{subdomain}{offerID}
        $bp->{$this->id}->offers_subdomain = 'offer';

        /** Single Offer Globals ********************************************* */
        // Are we viewing a single offer?
        if (bp_is_offer_component() && $offer_id = BP_Offer::offer_exists(bp_current_action())) {
            $bp->is_single_item = true;
            $current_offer_class = apply_filters('bp_offers_current_offer_class', 'BP_Offer');
            $this->current_offer = apply_filters('bp_offers_current_offer_object', new $current_offer_class(Array('id' => $offer_id)));
            $this->current_offer->slug = $bp->{$this->id}->offers_subdomain . $offer_id;

            // When in a single offer, the first action is bumped down one because of the
            // offer name, so we need to adjust this and set the group name to current_item.
            $bp->current_item = bp_current_action();
            $bp->current_action = bp_action_variable(0);
            array_shift($bp->action_variables);

            //Set if the user is owner of the offer
            bp_update_is_item_admin(bp_loggedin_user_id() == $this->current_offer->uid, 'offers');

            //echo "Current Action: ".$bp->current_action." OfferID: ".$offer_id. "   ". $bp->is_single_item. "Single item? ". bp_is_single_item() ; //die();
        } else {
            $this->current_offer = 0;
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
        // Add 'Offers' to the main navigation
        $main_nav = array(
            'name' => sprintf(__('Offers  <span>%s</span>', 'buddypress'), bp_get_total_offers_count_for_user()),
            'slug' => bp_get_offers_slug(),
            'position' => 80,
            'screen_function' => 'bp_offers_screen_one',
            'default_subnav_slug' => 'screen-one'
        );

        $offer_link = trailingslashit(bp_loggedin_user_domain() . bp_get_offers_slug());

        // Add a few subnav items under the main Example tab
        $sub_nav[] = array(
            'name' => __('Published Offers', 'cecom-offers'),
            'slug' => 'screen-one',
            'parent_url' => $offer_link,
            'parent_slug' => bp_get_offers_slug(),
            'screen_function' => 'bp_offers_screen_one',
            'position' => 10
        );

        // Add the subnav items to the friends nav item
        $sub_nav[] = array(
            'name' => __('Create Offer', 'cecom-offers'),
            'slug' => 'create-offer',
            'parent_url' => $offer_link,
            'parent_slug' => bp_get_offers_slug(),
            'screen_function' => 'bp_offers_create_offer',
            'position' => 20
        );

        parent::setup_nav($main_nav, $sub_nav);


        if (bp_is_offer_component() && bp_is_single_item()) {

            // Reset sub nav
            $sub_nav = array();

            // Add 'Groups' to the main navigation
            $main_nav = array(
                'name' => __('Home', 'buddypress'),
                'slug' => $this->current_offer->slug,
                'position' => -1, // Do not show in BuddyBar
                'screen_function' => 'offers_screen_offer_home',
                'default_subnav_slug' => $this->default_extension,
                'item_css_id' => $this->id
            );

            $offer_link = trailingslashit(bp_get_root_domain() . '/' . bp_get_offers_root_slug() . '/' . $this->current_offer->slug . '/');

            // If the user is offer ownner, then show the offer admin nav item
            if (bp_is_item_admin()) {
                global $bp;
                $sub_nav[] = array(
                    'name' => __('Admin', 'buddypress'),
                    'slug' => 'admin',
                    'parent_url' => $offer_link,
                    'parent_slug' => $this->current_offer->slug,
                    'screen_function' => 'offers_screen_offer_admin',
                    'position' => 40,
                    'user_has_access' => true,
                    'item_css_id' => 'admin'
                );
            }



            parent::setup_nav($main_nav, $sub_nav);
        }

        if (isset($this->current_offer->user_has_access)) {
            do_action('offers_setup_nav', $this->current_offer->user_has_access);
        } else {
            do_action('offers_setup_nav');
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
 *   function bp_offers_var_dump() {
 *   	  global $bp;
 * 	  var_dump( $bp->example );
 *   }
 *   add_action( 'bp_init', 'bp_offers_var_dump' );
 * It goes without saying that you should not do this on a production site!
 *
 * @package Offers Component
 */
function bp_offers_load_core_component() {
    global $bp;

    $bp->offers = new BP_Offers_Component();
}

add_action('bp_loaded', 'bp_offers_load_core_component', 1);
?>