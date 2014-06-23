<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_member_header' ); ?>

<div id="item-header-avatar" class="col-xs-4 col-md-4 fs-content-thumbnail">
	<a href="<?php bp_displayed_user_link(); ?>">

		<?php bp_displayed_user_avatar( 'type=full' ); ?>

	</a>
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="fs-have-thumbnail">

	<h2>
		<span><a href="<?php bp_displayed_user_link(); ?>"><?php bp_displayed_user_fullname(); 
		// ugly hack to display the "surname":
		$user_id = bp_displayed_user_id(); $prof = BP_XProfile_ProfileData::get_all_for_user( $user_id );  echo ' '.$prof['Surname']['field_data'].' '; echo '('; $username = bp_loggedin_user_username(); echo ')'; ?></a></span>
	</h2>

	<h3>
	<?php 
	global $wpdb;
	$query = "SELECT name, slug FROM wp_bp_groups WHERE id IN (SELECT group_id AS id FROM wp_bp_groups_members WHERE user_id = $user_id)";
	$orgs = $wpdb->get_results($query);
	
	foreach ($orgs as $o) 
		$links[] = '<a href="/cecommunity/groups/'.$o->slug.'">'.$o->name.'</a>';

	echo @join($links, ", ");
	?>
	</h3>
	
	<!-- 
	<span class="user-nicename label label-default">@<?php bp_displayed_user_username(); ?></span>
	<span class="activity label label-info"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>
 	-->
 	
	<?php do_action( 'bp_before_member_header_meta' ); ?>
<!-- 
	<div id="item-meta">

		<?php if ( bp_is_active( 'activity' ) ) : ?>

			<blockquote id="latest-update">

				<?php bp_activity_latest_update( bp_displayed_user_id() ); ?>

			</blockquote>

		<?php endif; ?>
 -->
		<div id="item-buttons">

			<?php do_action( 'bp_member_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php
		/***
		 * If you'd like to show specific profile fields here use:
		 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
		 */
		 do_action( 'bp_profile_header_meta' );

		 ?>

	</div><!-- #item-meta -->

</div><!-- #item-header-content -->

<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>