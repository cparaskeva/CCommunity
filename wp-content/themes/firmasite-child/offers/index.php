<?php
/**
 * CECommunity - Offers Directory
 *
 * @package CECommunity Offers Component
 */
?>

<?php
global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrap-multiselect-style');

global $bp;
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <?php do_action('bp_before_directory_offers_page'); ?>

        <form action="" method="post" id="offers-directory-form" class="dir-form">
            <h3  class="page-header"><?php _e('Offers Directory', 'firmasite'); ?></h3>

            <?php do_action('bp_before_directory_offers_content'); ?>

            <?php do_action('template_notices'); ?>
            <!-- Quick solution to fix the selected tab using $_Cookie[scope]-->
            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    <li  class="<?php echo ($_COOKIE['bp-offers-scope'] == "all" || (empty($_COOKIE['bp-offers-scope']) && empty($_COOKIE['bp-offers-scope']) ) ? "selected" : "") ?>" id="offers-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_offers_root_slug()); ?>"><?php printf(__('All Offers<span>%s</span>', 'buddypress'), bp_get_total_offers_count()); ?></a></li>


                    <?php if (is_user_logged_in() && bp_get_total_offers_count_for_user(bp_loggedin_user_id())) : ?>
                        <li class="<?php echo ($_COOKIE['bp-offers-scope'] == "personal" ? "selected" : "") ?>" id="offers-personal"><a href="<?php echo trailingslashit(bp_loggedin_user_domain() . bp_get_offers_slug() . '/my-offers'); ?>"><?php printf(__('My Offers <span>%s</span>', 'firmasite'), bp_get_total_offers_count_for_user(bp_loggedin_user_id())); ?></a></li>

                    <?php endif; ?>

                    <?php do_action('bp_offers_directory_offer_filter'); ?>

                </ul>
            </div><!-- .item-list-tabs -->
            <div id="offer-dir-search" class="dir-search" role="search">

                <br>  <?php bp_directory_offers_search_form(); ?> 

            </div><!-- #offer-dir-search -->

            <!-- Include the UI for the search form -->
            <?php include(get_stylesheet_directory() . "/offers/search.php"); ?>





            <div class="item-list-tabs" id="subnav" role="navigation">
                <ul class="nav nav-pills">
                    <?php do_action('bp_offers_directory_offer_types'); ?>

                    <li id="offers-order-select" class="last pull-right filter">
                        <label for="offers-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
                        <select id="offers-order-by">
                            <option <?php echo ($_COOKIE['bp-offers-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-offers-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-offers-filter'] == "offertype" ? "selected='selected'" : "") ?> value="offertype"><?php _e('Offer Type', 'firmasite'); ?></option> 

                            <?php do_action('bp_offers_directory_order_options'); ?>

                        </select>
                    </li>
                </ul>
            </div><!-- .item-list-tabs -->

            <div id="offers-dir-list" class="offers dir-list">
                <?php locate_template(array('offers/offers-loop.php'), true); ?>
            </div><!-- #offers-dir-list -->

            <?php do_action('bp_directory_offers_content'); ?>

            <?php wp_nonce_field('directory_offers', '_wpnonce-offers-filter'); ?>


            <?php do_action('bp_after_directory_offers_content'); ?>

        </form><!-- #offers-directory-form -->

        <?php do_action('bp_after_directory_offers'); ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php do_action('bp_after_directory_offers_page'); ?>

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>