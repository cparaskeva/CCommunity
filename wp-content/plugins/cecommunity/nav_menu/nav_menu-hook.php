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
	jQuery("#menu-topbar-right").append( jQuery(".menu-your-offers").remove() );
	/* "see your offers" link */
	var offers_url = "/cecommunity/members/<?php echo $current_user->user_nicename; ?>/offers/";
        jQuery("li.menu-edit-your-offers a").attr('href', offers_url);
	
	jQuery("#menu-topbar-right").append( jQuery(".menu-about").remove() );
	/* logout link */
	var logout_url = "<?php echo htmlspecialchars_decode(wp_logout_url()) ?>";
	
	jQuery("#menu-topbar-right").append( jQuery(".menu-log-out").remove() );
	jQuery(".menu-log-out a").attr('href', logout_url);

	/* "edit your profile" link */
	var profile_url = "/cecommunity/members/<?php echo $current_user->user_nicename; ?>/profile/";
	jQuery("li.menu-edit-your-profile a").attr('href', profile_url);
	
	
	/* "publish an offer" page */
	if (document.URL.indexOf("/publish-an-offer/") > 0) {
		jQuery("div.entry-content a").each(function() {
			var url = jQuery(this).attr('href');
			var url2 = url.replace('\/members\/cecom\/', '\/members\/<?php echo $current_user->user_nicename; ?>\/');
			jQuery(this).attr('href', url2);
		});
	}
	
});

//-->
</script>

<?php 
}
add_action('wp_footer', 'jquery_menu_transform');
