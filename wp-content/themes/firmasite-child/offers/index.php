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
global $bp;
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <?php do_action('bp_before_directory_example'); ?>

        <form action="" method="post" id="offers-directory-form" class="dir-form">

            <h3><?php _e('Offers Directory', 'firmasite'); ?></h3>

            <?php do_action('bp_before_directory_offers_content'); ?>

            <?php do_action('template_notices'); ?>

            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    <li class="selected" id="offers-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_offers_root_slug()); ?>"><?php printf(__('All Offers<span>%s</span>', 'buddypress'), bp_get_total_offers_count()); ?></a></li>


                    <?php if (is_user_logged_in() && bp_get_total_offers_count_for_user(bp_loggedin_user_id())) : ?>
                    <li id="offers-personal"><a href="<?php echo trailingslashit(bp_loggedin_user_domain() . bp_get_offers_slug(). '/my-offers'); ?>"><?php printf(__('My Offers <span>%s</span>', 'firmasite'), bp_get_total_offers_count_for_user(bp_loggedin_user_id())); ?></a></li>

                    <?php endif; ?>

                    <?php do_action('bp_offers_directory_example_filter'); ?>

                </ul>
            </div><!-- .item-list-tabs -->

            <div id="offers-dir-list" class="offers dir-list">

                <?php //bp_core_load_template( 'example/example-loop' );  ?>
                <?php locate_template(array('offers/offers-loop.php'), true); ?>

            </div><!-- #examples-dir-list -->

            <?php do_action('bp_directory_offers_content'); ?>


            <?php do_action('bp_after_directory_offers_content'); ?>

        </form><!-- #offers-directory-form -->

        <?php do_action('bp_after_directory_offers'); ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php do_action('bp_after_directory_offers_page'); ?>

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>