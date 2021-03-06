<?php
/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
global $firmasite_settings;
get_header('buddypress');
?>

<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <?php do_action('bp_before_member_home_content'); ?>

        <div id="item-header" class="well well-sm media clearfix" role="complementary">

            <?php locate_template(array('members/single/member-header.php'), true); ?>

        </div><!-- #item-header -->

        <div id="item-nav" class="navbar <?php
        if (isset($firmasite_settings["menu-style"]) && "alternative" == $firmasite_settings["menu-style"]) {
            echo " navbar-default";
        } else {
            echo "  navbar-inverse";
        }
        ?>">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".bp-profile-nav-collapse">
                    <i class="icon-bar"></i>
                    <i class="icon-bar"></i>
                    <i class="icon-bar"></i>
                </button>
            </div>
            <div class="collapse navbar-collapse bp-profile-nav-collapse item-list-tabs no-ajax" id="object-nav" role="navigation">
                <ul class="nav navbar-nav">

                    <?php bp_get_displayed_user_nav(); ?>

                    <?php do_action('bp_member_options_nav'); ?>

                </ul>
            </div>
        </div><!-- #item-nav -->

        <div id="item-body">

            <?php
            do_action('bp_before_member_body');

            if (bp_is_user_activity() || !bp_current_component()) :
                locate_template(array('members/single/activity.php'), true);

            elseif (bp_is_user_blogs()) :
                locate_template(array('members/single/blogs.php'), true);

            elseif (bp_is_user_friends()) :
                locate_template(array('members/single/friends.php'), true);

            elseif (bp_is_user_groups()) :
                locate_template(array('members/single/groups.php'), true);

            elseif (bp_is_user_messages()) :
                locate_template(array('members/single/messages.php'), true);

            elseif (bp_is_user_profile()) :
                locate_template(array('members/single/profile.php'), true);

            elseif (bp_is_user_forums()) :
                locate_template(array('members/single/forums.php'), true);

            elseif (bp_is_user_settings()) :
                locate_template(array('members/single/settings.php'), true);
            elseif (bp_is_user_notifications()) :
                locate_template(array('members/single/notifications.php'), true);
            /*
             * CECommunity Platform Extensions
             */

            /* CECommunity Offers Component */
            elseif (bp_is_offer_component()) :
                locate_template(array('members/single/offers.php'), true);
            /* CECommunity Patents_Licenses Component */
            elseif (bp_is_patent_license_component()) :
                locate_template(array('members/single/patents_licenses.php'), true);
            /* CECommunity Tools_Facilities Component */
            elseif (bp_is_tool_facility_component()) :
                locate_template(array('members/single/tools_facilities.php'), true);
            /* CECommunity Alerts Component */
            elseif (bp_is_alert_component()) :
                locate_template(array('members/single/alerts.php'), true);

            /* CECommunity Challenges Component */
            elseif (bp_is_challenge_component()) :
                 locate_template(array('members/single/challenges.php'), true);
 
            // If nothing sticks, load a generic template
            else :
                locate_template(array('members/single/plugins.php'), true);

            endif;

            do_action('bp_after_member_body');
            ?>

        </div><!-- #item-body -->

        <?php do_action('bp_after_member_home_content'); ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>
