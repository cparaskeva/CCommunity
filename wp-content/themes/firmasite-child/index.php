<?php
/**
 * @package firmasite
 */
global $firmasite_settings;

get_header();
?>



<div id="primary" class="content-area clearfix <?php echo $firmasite_settings["layout_primary_class"]; ?>">

    <!-- @Developer: Consider using custom_javascripts() function in ordered
    to include custom  css & js files (located in ./firmasite-child/functions.php)
    
    CSS Folder: "./firmasite-child/assets/css" 
    JS Folder:  "./firmasite-child/assets/js"
    PHP use:  <?php //echo get_stylesheet_directory_uri()."/assets/" ?>
    -->
    
    
<!-- Include whatever content you want....-->


</div><!-- #primary .content-area -->



<!-- DO NOT MODIFY OR REMOVE THE FOLLOWING FUNCTIONS -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>