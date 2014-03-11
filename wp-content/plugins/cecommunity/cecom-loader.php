<?php

/**
 * @package Organization
 * @version 1.0
 *
  /*
  Plugin Name: CECommunity Organisation
  Plugin URI: http://
  Description: Manages the organisation profiles of the CECommunity platform.
  Author: Chris Paraskeva
  Version: 1.0
 */
if (!class_exists('CECommunity')) :

    /**
     * Main CECommunity Class
     *
     */
    class CECommunity {
    
        /* Using Singleton Pattern */

        /**
         * @var CECommunity Instance
         */
        private static $instance;

        /**
         * Main CECommunity Instance
         *
         *
         * Insures that only one instance of CECommunity exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         *
         * @staticvar array $instance
         * @uses CECommunity::constants() Setup the constants (mostly deprecated)
         * @uses CECommunity::setup_globals() Setup the globals needed
         * @uses CECommunity::includes() Include the required files
         * @uses CECommunity::setup_actions() Setup the hooks and actions
         * @see cecommunity()
         *
         * @return CECommunity The one true CECommunity
         */
        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new CECommunity;
                self::$instance->includes();
                self::$instance->setup_actions();
            }
            return self::$instance;
        }

        /**
         * A dummy constructor to prevent CECommunity from being loaded more than once.
         *
         * @see CECommunity::instance()
         * @see cecommunity()
         */
        private function __construct() { /* Do nothing here */
        }

        private function includes() {
            require( dirname(__FILE__) . '/organization/cecom-organization-core.php' );
            require( dirname(__FILE__) . '/login_lostpassword/login-form-action-hook.php' );
            require( dirname(__FILE__) . '/nav_menu/nav_menu-hook.php' );
        }

        private function setup_actions() {
          //  do_action("cecom_setup_components");
        }

    }

    /**
     * The main function responsible for returning the one true CECommunity Instance
     * to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * Example: <?php $cecom = cecommunity(); ?>
     *
     * @return BuddyPress The one true BuddyPress Instance
     */
    function cecommunity() {
        return CECommunity::instance();
    }

    //Globalize the CECommunity Instance
    $GLOBALS['cecom'] = &cecommunity();

endif;
?>
