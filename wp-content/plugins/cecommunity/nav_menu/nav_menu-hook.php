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

	jQuery("#menu-topbar-right").append( jQuery(".menu-your-profile").remove() );
	jQuery("#menu-topbar-right").append( jQuery(".menu-about").remove() );

	/* "edit your profile" link */
	var profile_url = "/cecommunity/members/<?php echo $current_user->user_login; ?>/profile/";
	jQuery("li.menu-edit-your-profile a").attr('href', profile_url);
	
	/* logout link */
	var logout_url = "<?php echo htmlspecialchars_decode(wp_logout_url()) ?>";
	jQuery("li.menu-log-out a").attr('href', logout_url);

	/* "publish an offer" page */
	if (document.URL.indexOf("/publish-an-offer/") > 0) {
		jQuery("div.entry-content a").each(function() {
			var url = jQuery(this).attr('href');
			var url2 = url.replace('\/members\/cecom\/', '\/members\/<?php echo $current_user->user_login; ?>\/');
			/*console.log(url); 	console.log(url2);*/
			jQuery(this).attr('href', url2);
		});
	}
	
});

//-->
</script>

<?php 
}
add_action('wp_footer', 'jquery_menu_transform');
