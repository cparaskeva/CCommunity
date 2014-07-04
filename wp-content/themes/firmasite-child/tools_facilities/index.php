<?php
/**
 * CECommunity - Tools & Facilities Directory
 *
 * @package CECommunity Tools_Facilities Component
 */
?>

<?php
global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');

global $bp;
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

<?php do_action('bp_before_directory_tools_facilities_page'); ?>

        <form action="" method="post" id="tools_facilities-directory-form" class="dir-form">
            <h3  id="tools_facilities-header" class="page-header"><?php _e('Tools & Facilities', 'firmasite'); ?></h3>

<?php do_action('bp_before_directory_tools_facilities_content'); ?>

<?php do_action('template_notices'); ?>
            <!-- Quick solution to fix the selected tab using $_Cookie[scope]-->
            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    <li  class="<?php echo ($_COOKIE['bp-tools_facilities-scope'] == "all" || (empty($_COOKIE['bp-tools_facilities-scope']) && empty($_COOKIE['bp-tools_facilities-scope']) ) ? "selected" : "") ?>" id="tools_facilities-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_tools_facilities_root_slug()); ?>"><?php printf(__('All Tools & Facilities<span>%s</span>', 'buddypress'), bp_get_total_tools_facilities_count()); ?></a></li>


<?php if (is_user_logged_in() && bp_get_total_tools_facilities_count_for_user(bp_loggedin_user_id())) : ?>
                        <li class="<?php echo ($_COOKIE['bp-tools_facilities-scope'] == "personal" ? "selected" : "") ?>" id="tools_facilities-personal"><a href="<?php echo trailingslashit(bp_loggedin_user_domain() . bp_get_tools_facilities_slug() . '/my-tools_facilities'); ?>"><?php printf(__('My Tools & Facilities <span>%s</span>', 'firmasite'), bp_get_total_tools_facilities_count_for_user(bp_loggedin_user_id())); ?></a></li>

                    <?php endif; ?>

<?php do_action('bp_tools_facilities_directory_tool_facility_filter'); ?>

                </ul>
            </div><!-- .item-list-tabs -->
            <div id="tool_facility-dir-search" class="dir-search" role="search">

                <br>  <?php bp_directory_tools_facilities_search_form(); ?> 

            </div><!-- #tool_facility-dir-search -->

            <!-- Include the UI for the search form -->
<?php include(get_stylesheet_directory() . "/tools_facilities/search.php"); ?>





            <div class="item-list-tabs" id="subnav" role="navigation">
                <ul class="nav nav-pills">
<?php do_action('bp_tools_facilities_directory_tool_facility_types'); ?>

                    <li id="tools_facilities-order-select" class="last pull-right filter">
                        <label for="tools_facilities-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
                        <select id="tools_facilities-order-by">
                            <option <?php echo ($_COOKIE['bp-tools_facilities-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-tools_facilities-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>

<?php do_action('bp_tools_facilities_directory_order_options'); ?>

                        </select>
                    </li>
                </ul>
            </div><!-- .item-list-tabs -->

            <div id="tools_facilities-dir-list" class="tools_facilities dir-list">
<?php locate_template(array('/tools_facilities/tools_facilities-loop.php'), true); ?>
            </div><!-- #tools_facilities-dir-list -->

                <?php do_action('bp_directory_tools_facilities_content'); ?>

<?php wp_nonce_field('directory_tools_facilities', '_wpnonce-tools_facilities-filter'); ?>


            <?php do_action('bp_after_directory_tools_facilities_content'); ?>

        </form><!-- #tools_facilities-directory-form -->

            <?php do_action('bp_after_directory_tools_facilities'); ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php do_action('bp_after_directory_tools_facilities_page'); ?>

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>