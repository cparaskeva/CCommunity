<?php
/**
 * BuddyPress - Organisations Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');
wp_enqueue_style('bootstrap-multiselect-style');
?>

<?php do_action('bp_before_directory_groups_page'); ?>

<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <?php do_action('bp_before_directory_groups'); ?>

        <form action="" method="post" id="groups-directory-form" class="dir-form">

            <h3 class="page-header"><?php _e('Organisations Directory', 'firmasite'); ?><?php if (is_user_logged_in() && bp_user_can_create_groups()) : ?> &nbsp;<a class="button btn btn-default" href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create'); ?>"><?php _e('Create a Group', 'firmasite'); ?></a><?php endif; ?></h3>

            <?php do_action('bp_before_directory_groups_content'); ?>

            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    <li class="selected" id="groups-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_groups_root_slug()); ?>"><?php printf(__('All Organisations <span>%s</span>', 'firmasite'), bp_get_total_group_count()); ?></a></li>

                    <?php if (is_user_logged_in() && bp_get_total_group_count_for_user(bp_loggedin_user_id())) : ?>

                        <li id="groups-personal"><a href="<?php echo trailingslashit(bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups'); ?>"><?php printf(__('My Organisation <span>%s</span>', 'firmasite'), bp_get_total_group_count_for_user(bp_loggedin_user_id())); ?></a></li>

                    <?php endif; ?>

                    <?php do_action('bp_groups_directory_group_filter'); ?>

                </ul>
            </div><!-- .item-list-tabs -->

            <div id="group-dir-search" class="dir-search" role="search">

                <br> <?php bp_directory_groups_search_form(); ?>

            </div><!-- #group-dir-search -->

            <!-- Include the UI for the search form -->
            <?php include(get_stylesheet_directory() . "/groups/search.php"); ?>

            <?php do_action('template_notices'); ?>

            <div class="item-list-tabs" id="subnav" role="navigation">
                <ul class="nav nav-pills">

                    <?php do_action('bp_groups_directory_group_types'); ?>

                    <li id="groups-order-select" class="last pull-right filter">

                        <label for="groups-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
                        <select id="groups-order-by">
                            <option value="active"><?php _e('Last Active', 'firmasite'); ?></option>
                            <option value="popular"><?php _e('Most Members', 'firmasite'); ?></option>
                            <option value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                            <option value="alphabetical"><?php _e('Alphabetical', 'firmasite'); ?></option>

                            <?php do_action('bp_groups_directory_order_options'); ?>

                        </select>
                    </li>
                </ul>
            </div>

            <div id="groups-dir-list" class="groups dir-list">

                <?php locate_template(array('groups/groups-loop.php'), true); ?>

            </div><!-- #groups-dir-list -->

            <?php do_action('bp_directory_groups_content'); ?>

            <?php wp_nonce_field('directory_groups', '_wpnonce-groups-filter'); ?>

            <?php do_action('bp_after_directory_groups_content'); ?>

        </form><!-- #groups-directory-form -->

        <?php do_action('bp_after_directory_groups'); ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php do_action('bp_after_directory_groups_page'); ?>

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>