<?php 

/**
 * jQuery function that moves the 2 last menu entries
 * These 2 menu entries SHOULD be called "Your profile" and "About"
 */

function jquery_menu_transform() {
	
	$current_user = wp_get_current_user();
	
?>

<script type="text/javascript">
<!--

jQuery( document ).ready( function() {
	jQuery("#nav-main").append('<ul id="menu-topbar-right" class="nav navbar-nav navbar-right"></ul>');

	jQuery("#menu-topbar-right").append( jQuery(".menu-my-profile").remove() );
	jQuery("#menu-topbar-right").append( jQuery(".menu-my-offers").remove() );
	jQuery("#menu-topbar-right").append( jQuery(".menu-about").remove() );

	/* logout link */
	var logout_url = "<?php echo htmlspecialchars_decode(wp_logout_url()) ?>";
	jQuery("#menu-topbar-right").append( jQuery(".menu-log-out").remove() );
	jQuery(".menu-log-out a").attr('href', logout_url);

	/* "edit your profile" link */
	var profile_url = "/cecommunity/members/<?php echo $current_user->user_nicename; ?>/profile/";
	jQuery(".menu-my-profile a").attr('href', profile_url);
	
	
	
	
});

//-->
</script>

<?php 
}
add_action('wp_footer', 'jquery_menu_transform');
