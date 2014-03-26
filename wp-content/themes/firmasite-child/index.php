<?php
/**
 * @package firmasite
 */
global $firmasite_settings;

get_header();

$current_user = wp_get_current_user();


/*
echo 'Username: ' . $current_user->user_login . "\n";
echo 'User email: ' . $current_user->user_email . "\n";
echo 'User first name: ' . $current_user->user_firstname . "\n";
echo 'User last name: ' . $current_user->user_lastname . "\n";
echo 'User display name: ' . $current_user->display_name . "\n";
echo 'User ID: ' . $current_user->ID . "\n";
*/


$create_offer_product = "/cecommunity/members/".$current_user->user_login."/offers/create-offer/?offer_type=1/";
$create_offer_project = "/cecommunity/members/".$current_user->user_login."/offers/create-offer/?offer_type=2/";
$create_offer_funding = "/cecommunity/members/".$current_user->user_login."/offers/create-offer/?offer_type=3/";

$org_profile = "/cecommunity/members/".$current_user->user_login."/groups/";

?>



<div id="primary" class="content-area clearfix <?php echo $firmasite_settings["layout_primary_class"]; ?>">

    <!-- @Developer: Consider using custom_javascripts() function in ordered
    to include custom  css & js files (located in ./firmasite-child/functions.php)
    
    CSS Folder: "./firmasite-child/assets/css" 
    JS Folder:  "./firmasite-child/assets/js"
    PHP use:  <?php //echo get_stylesheet_directory_uri()."/assets/" ?>
    -->
    
    
    <style>
    
    body {
    -webkit-animation-delay: 0.1s;
    -webkit-animation-name: fontfix;
    -webkit-animation-duration: 0.1s;
    -webkit-animation-iteration-count: 1;
    -webkit-animation-timing-function: linear;
}

@-webkit-keyframes fontfix {
    from { opacity: 1; }
    to   { opacity: 1; }
}
    
    
    .borders {border:solid 1px #eee;border-radius:10px; margin-bottom:5px}
    .no-margin-left {margin-left:0}
    .offers li, .organisations li {list-style-type: none; padding-bottom:7px}
    </style>
    
	<div class="col-md-12 borders">
		<h1>Welcome ! </h1>
	
		<div class="row">
			<div class="col-md-6">
				<h4>SEARCH</h4>
				- Search for <a href="/cecommunity/groups/">organisations</a><br>
				- Search for <a href="#">collaborations</a>, either to develop <a href="/cecommunity/groups/?offer_type=1">new products or services</a> or to cooperate on <a href="/cecommunity/groups/?offer_type=2">funded projects</a>.<br> 
				- Search for <a href="#">patents, fundings, tools</a> for rent.<br>
			</div>
			
			<div class="col-md-6">
				<h4>OFFER</h4>
				- Offer a collaboration on a <a href="<?php echo $create_offer_product; ?>">product</a> or a <a href="<?php echo $create_offer_project; ?>">project</a>.<br>
				- Offer <a href="<?php echo $create_offer_funding; ?>">patents, fundings, tools</a> for rent.<br>
				<br>
				<h4>YOUR PROFILE</h4>
				- Fill in the <a href="<?php echo $org_profile; ?>">profile</a> of your organisation, to be sure you can be found by other organisations.
			</div>
		</div>
		
		<br>
	</div>

	<div class="row no-margin-left">
		<div class="col-md-6 borders">
			<span style="float: right;margin-top: 27px"><a href="/cecommunity/news-from-the-influencers/">More news</a></span>
			<h3>News from the influencers</h3>
			
			
			<a class="twitter-timeline" href="https://twitter.com/centralcomm1"  height="400"  data-widget-id="438354056449241088" data-favorites-screen-name="centralcomm1"></a>
			 <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


			
		</div>

		<div class="col-md-6">
		
		<div class="borders offers" style="padding:0 10px">
			<h3>Last offers</h3>
			
			<?php 
			
			function getLatestOffers($nbOffers = 5) {
				global $wpdb;

				$query = "SELECT o.*, ot.description AS type, g.name, g.slug  ".
								"FROM ext_offer o JOIN ext_offer_type ot ON o.type_id = ot.id JOIN wp_bp_groups g ON o.gid = g.id ".
								"ORDER BY o.date DESC LIMIT $nbOffers";
    		
				$offers = $wpdb->get_results($query);
				//syslog(LOG_INFO, var_export($offers, true));
				return $offers;
			}
			
			$max_desc_len = 30;
			$offers = getLatestOffers();
			foreach ($offers as $off) {
				$org_slug =  bp_get_root_domain().'/groups/'.$off->slug;
				$org = $off->name;
				
				$off_type = $off->type;
				$o_url = bp_get_root_domain().'/offers/offer'.$off->id;
				$desc = $off->description;
				if (strlen($desc) > $max_desc_len)
					$desc = substr($desc, 0, $max_desc_len) . '...';
				
				echo "<li><a href='$org_slug'>$org</a> - $off_type : ".
					"<a href='$o_url'>$desc</a></li>";
			}
			?>
			
		</div>
		
		<div class="borders organisations" style="padding:0 10px">
			<h3>Last registered organisations</h3>
			
			
			<?php 
			$orgs = getLatestOrganisations();
			foreach ($orgs as $org) {
				$url = bp_get_root_domain().'/groups/'.$org->slug;
				echo "<li><a href='$url'>$org->name</a></li>";
			}
			?>
			
		</div>
		
		</div>
		
	</div>


</div><!-- #primary .content-area -->



<!-- DO NOT MODIFY OR REMOVE THE FOLLOWING FUNCTIONS -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>