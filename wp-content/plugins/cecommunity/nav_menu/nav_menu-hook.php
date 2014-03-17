<?php 

/**
 * jQuery function that moves the 2 last menu entries
 * These 2 menu entries SHOULD be called "Your profile" and "About"
 */

function jquery_menu_transform() {
?>

<script type="text/javascript">
<!--

jQuery( document ).ready( function() {
	jQuery("#nav-main").append('<ul id="menu-topbar-right" class="nav navbar-nav navbar-right"></ul>');

	jQuery("#menu-topbar-right").append( jQuery(".menu-your-profile").remove() );
	jQuery("#menu-topbar-right").append( jQuery(".menu-about").remove() );
});

//-->
</script>

<?php 
}
add_action('wp_footer', 'jquery_menu_transform');
