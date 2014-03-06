<?php 

/**
 *
 */

function your_function() {
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
add_action('wp_footer', 'your_function');

/*
 
 add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class($classes, $item){
	if(is_single() && $item->title == "Blog"){ //Notice you can change the conditional from is_single() and $item->title
		$classes[] = "special-class";
	}
	return $classes;
}
  
  
*/

// http://codex.wordpress.org/Function_Reference/register_nav_menus
// http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_nav_menu_args
// http://codex.wordpress.org/Plugin_API/Filter_Reference/menu_order
// http://codex.wordpress.org/Plugin_API/Filter_Reference


/// SOURCE:  https://core.trac.wordpress.org/browser/tags/3.8.1/src/wp-includes/nav-menu-template.php#L0
// wp_nav_menu_objects
// wp_nav_menu_items
// wp_nav_menu

/*
add_filter('wp_nav_menu', 'transform_nav_menu');

function transform_nav_menu($args) {
	syslog(LOG_INFO, var_export($args, 1));
}
*/