<?php
/**
 * CECommunity - Alerts Directory
 *
 * @package CECommunity Alerts Component
 */
?>

<?php
//Before anything happens check for alert modifications
bp_alerts_check_modifications();

global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');

//do_action("cecom_alerts");
print_r(wp_get_schedules());
//echo "Next event: ".wp_next_scheduled("cecom_alerts");
global $bp;
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <?php do_action('bp_before_directory_alerts_page'); ?>

        <form action="" method="post" id="alerts-directory-form" class="dir-form">
            <h3  id="alerts-header" class="page-header"><?php _e('Alerts Directory ', 'firmasite'); ?></h3>
            <?php do_action('template_notices'); ?>

            <div id="tool_facility-dir-search" class="dir-search" role="search">

                <?php bp_directory_alerts_search_form(); ?> 

            </div><!-- #tool_facility-dir-search -->
            <?php do_action('bp_before_directory_alerts_content'); ?>


            <!-- Quick solution to fix the selected tab using $_Cookie[scope]-->
            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    
                    <?php if (current_user_can('manage_options')):?>
                    
                    <li  class="<?php echo ($_COOKIE['bp-alerts-scope'] == "all" || (empty($_COOKIE['bp-alerts-scope']) && empty($_COOKIE['bp-alerts-scope']) ) ? "selected" : "") ?>" id="alerts-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_alerts_root_slug()); ?>"><?php printf(__('All Alerts<span>%s</span>', 'buddypress'), bp_get_total_alerts_count()); ?></a></li>
                    <?php endif; ?>

                    <?php if (is_user_logged_in() && bp_get_total_alerts_count_for_user(bp_loggedin_user_id())) : ?>
                        <li class="<?php echo ($_COOKIE['bp-alerts-scope'] == "personal" ? "selected" : "") ?>" id="alerts-personal"><a href="<?php echo trailingslashit(bp_loggedin_user_domain() . bp_get_alerts_slug() . '/my-alerts'); ?>"><?php printf(__('My Alerts <span>%s</span>', 'firmasite'), bp_get_total_alerts_count_for_user(bp_loggedin_user_id())); ?></a></li>

                    <?php endif; ?>

                    <?php do_action('bp_alerts_directory_tool_facility_filter'); ?>

                </ul>
            </div><!-- .item-list-tabs -->


            <!-- Include the UI for the search form -->
            <?php //include(get_stylesheet_directory() . "/alerts/search.php");  ?>





            <div class="item-list-tabs" id="subnav" role="navigation">
                <ul class="nav nav-pills">
                    <?php do_action('bp_alerts_directory_tool_facility_types'); ?>

                    <li id="alerts-order-select" class="last pull-right filter">
                        <label for="alerts-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
                        <select id="alerts-order-by">
                            <option <?php echo ($_COOKIE['bp-alerts-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-alerts-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>

                            <?php do_action('bp_alerts_directory_order_options'); ?>

                        </select>
                    </li>
                </ul>
            </div><!-- .item-list-tabs -->

            <div id="alerts-dir-list" class="alerts dir-list">
                <?php locate_template(array('/alerts/alerts-loop.php'), true); ?>
            </div><!-- #alerts-dir-list -->

            <?php do_action('bp_directory_alerts_content'); ?>

            <?php wp_nonce_field('directory_alerts', '_wpnonce-alerts-filter'); ?>

        </form><!-- #alerts-directory-form -->
    </div><!-- .padder -->
</div><!-- #content -->


<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>