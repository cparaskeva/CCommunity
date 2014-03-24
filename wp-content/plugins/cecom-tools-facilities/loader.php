<?php
/*
Plugin Name: CECommunity Tools & Facilities 
Plugin URI: http://
Description: This component is used to enable tools & facilities rent to the members of the platform.
Version: 1.0
Revision Date: MMMM DD, YYYY
Requires at least: BuddyPress version > 1.8 
Author: Chris Paraskeva
Network: true
*/

// Define a constant that can be checked to see if the component is installed or not.
define( 'BP_TOOLS_FACILITIES_IS_INSTALLED', 1 );

// Define a constant that will hold the current version number of the component
// This can be useful if you need to run update scripts or do compatibility checks in the future
define( 'BP_TOOLS_FACILITIES_VERSION', '1.0' );

// Define a constant that we can use to construct file paths throughout the component
define( 'BP_TOOLS_FACILITIES_PLUGIN_DIR', dirname( __FILE__ ) );

/* Only load the component if BuddyPress is loaded and initialized. */
function bp_tools_facilities_init() {
	// Because our loader file uses BP_Component, it requires BP 1.5 or greater.
	if ( version_compare( BP_VERSION, '1.3', '>' ) )
		require( dirname( __FILE__ ) . '/includes/cecom-tools_facilities-loader.php' );
}
add_action( 'bp_include', 'bp_tools_facilities_init' );

/* Put setup procedures to be run when the plugin is activated in the following function */
function bp_tools_facilities_activate() {

}
register_activation_hook( __FILE__, 'bp_tools_facilities_activate' );

/* On deacativation, clean up anything your component has added. */
function bp_tools_facilities_deactivate() {
	/* You might want to delete any options or tables that your component created. */
}
register_deactivation_hook( __FILE__, 'bp_tools_facilities_deactivate' );
?>
