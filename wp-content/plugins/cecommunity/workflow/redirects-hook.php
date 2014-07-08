<?php 

/*
 * This function redirects to the home/front page instead of wp_login.php
 */
function redirect_to_front_page() {
	$redirection_url = bp_get_root_domain();
	syslog(LOG_INFO, "redirecting to $redirection_url");
	wp_redirect($redirection_url);
	exit;
}
add_action('wp_logout', 'redirect_to_front_page');


