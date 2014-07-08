<?php
/**
 * CECommunity - Patents & Licenses Directory
 *
 * @package CECommunity Patents_Licenses Component
 */
?>

<?php
global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');
wp_enqueue_style('bootstrap-multiselect-style');

global $bp;
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

<?php do_action('bp_before_directory_patents_licenses_page'); ?>

        <form action="" method="post" id="patents_licenses-directory-form" class="dir-form">
            <h3  id="patents_licenses-header"><?php _e('Patents & Licenses', 'firmasite'); ?></h3>
<div class="page-header">List of all published offers about patents and licenses.</div>
	    <div style="height:20px;"></div>

<?php do_action('bp_before_directory_patents_licenses_content'); ?>

<?php do_action('template_notices'); ?>
            <!-- Quick solution to fix the selected tab using $_Cookie[scope]-->
            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    <li  class="<?php echo ($_COOKIE['bp-patents_licenses-scope'] == "all" || (empty($_COOKIE['bp-patents_licenses-scope']) && empty($_COOKIE['bp-patents_licenses-scope']) ) ? "selected" : "") ?>" id="patents_licenses-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_patents_licenses_root_slug()); ?>"><?php printf(__('All Patents & Licenses <span>%s</span>', 'buddypress'), bp_get_total_patents_licenses_count()); ?></a></li>


<?php if (is_user_logged_in() && bp_get_total_patents_licenses_count_for_user(bp_loggedin_user_id())) : ?>
                        <li class="<?php echo ($_COOKIE['bp-patents_licenses-scope'] == "personal" ? "selected" : "") ?>" id="patents_licenses-personal"><a href="<?php echo trailingslashit(bp_loggedin_user_domain() . bp_get_patents_licenses_slug() . '/my-patents_licenses'); ?>"><?php printf(__('My Patents & Licenses <span>%s</span>', 'firmasite'), bp_get_total_patents_licenses_count_for_user(bp_loggedin_user_id())); ?></a></li>

                    <?php endif; ?>

<?php do_action('bp_patents_licenses_directory_patent_license_filter'); ?>

                </ul>
            </div><!-- .item-list-tabs -->
           <div id="patent_license-dir-search" class="dir-search" role="search">

                <br>  <?php bp_directory_patents_licenses_search_form(); ?> 

            </div><!-- #patent_license-dir-search -->

            <!-- Include the UI for the search form -->
<?php include(get_stylesheet_directory() . "/patents_licenses/search.php"); ?>





            <div class="item-list-tabs" id="subnav" role="navigation">
                <ul class="nav nav-pills">
<?php do_action('bp_patents_licenses_directory_patent_license_types'); ?>

                    <li id="patents_licenses-order-select" class="last pull-right filter">
                        <label for="patents_licenses-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
                        <select id="patents_licenses-order-by">
                            <option <?php echo ($_COOKIE['bp-patents_licenses-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-patents_licenses-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-patents_licenses-filter'] == "patent_licensetype" ? "selected='selected'" : "") ?> value="patent_licensetype"><?php _e('Type', 'firmasite'); ?></option> 

<?php do_action('bp_patents_licenses_directory_order_options'); ?>

                        </select>
                    </li>
                </ul>
            </div><!-- .item-list-tabs -->

            <div id="patents_licenses-dir-list" class="patents_licenses dir-list">
<?php locate_template(array('/patents_licenses/patents_licenses-loop.php'), true); ?>
            </div><!-- #patents_licenses-dir-list -->

                <?php do_action('bp_directory_patents_licenses_content'); ?>

<?php wp_nonce_field('directory_patents_licenses', '_wpnonce-patents_licenses-filter'); ?>


            <?php do_action('bp_after_directory_patents_licenses_content'); ?>

        </form><!-- #patents_licenses-directory-form -->

            <?php do_action('bp_after_directory_patents_licenses'); ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php do_action('bp_after_directory_patents_licenses_page'); ?>

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>