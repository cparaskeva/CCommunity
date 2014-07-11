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

            <h3 id="offers-header"><?php _e('Organisations', 'firmasite'); ?></h3>

	    <div class="page-header">List of all registered organisations.</div>
	    <div style="height:20px;"></div>

            <?php do_action('bp_before_directory_groups_content'); ?>

            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    <li class="selected" id="groups-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_groups_root_slug()); ?>"><?php printf(__('All Organisations <span>%s</span>', 'firmasite'), bp_get_total_group_count()); ?></a></li>

     
                    <?php do_action('bp_groups_directory_group_filter'); ?>

                </ul>
 		</div><!-- .item-list-tabs -->
		
		<div id="group-dir-search" class="dir-search" role="search">

                  <br/> <?php bp_directory_groups_search_form(); ?>

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
                            
                            <option value="popular"><?php _e('Organisation size', 'firmasite'); ?></option>
                            <option value="newest"><?php _e('Newly created', 'firmasite'); ?></option>
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