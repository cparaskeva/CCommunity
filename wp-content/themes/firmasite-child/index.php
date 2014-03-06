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
    
    
    <style>
    .borders {border:solid 1px #eee;border-radius:10px; margin-bottom:5px}
    .no-margin-left {margin-left:0}
    </style>
    
	<div class="col-md-12 borders">
		<h2>Text with links to various main sections</h2>
		<br><br><br><br><br><br>
	</div>

	<div class="row no-margin-left">
		<div class="col-md-6 borders">
			<h2>News from the influencers</h2>
			
			
			<a class="twitter-timeline" href="https://twitter.com/centralcomm1"  height="300"  data-widget-id="438354056449241088" data-favorites-screen-name="centralcomm1"></a>
			
			<a class="twitter-timeline" href="https://twitter.com/centralcomm1"  height="300"  data-widget-id="438354056449241088" data-favorites-screen-name="matteomoci"></a>
			
			<a class="twitter-timeline" href="https://twitter.com/centralcomm1"  height="300"  data-widget-id="438354056449241088" data-favorites-screen-name="CybionIT"></a>
			
			<a class="twitter-timeline" href="https://twitter.com/centralcomm1"  height="300"  data-widget-id="438354056449241088" data-favorites-screen-name="triverio"></a>
			
			 
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


			
		</div>

		<div class="col-md-6">
		
		<div class="borders" style="padding:0 10px">
			<h2>Last offers</h2>
			<br><br><br><br><br><br>
		</div>
		
		<div class="borders" style="padding:0 10px">
			<h2>Last registered organisations</h2>
			<br><br><br><br><br><br>
		</div>
		
		</div>
		
	</div>


</div><!-- #primary .content-area -->



<!-- DO NOT MODIFY OR REMOVE THE FOLLOWING FUNCTIONS -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>