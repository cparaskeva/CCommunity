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
?>


<div class="item-list-tabs no-ajax" id="subnav" role="navigation">

    <ul class="nav nav-pills">
        <?php if (bp_is_my_profile()) bp_get_options_nav(); ?>


        <?php do_action('bp_offers_directory_example_filter'); ?>

    </ul>



    <?php do_action('bp_directory_example_content'); ?>

    <?php do_action('bp_after_directory_example_content'); ?>

    <?php do_action('bp_after_directory_example'); ?>

</div><!-- .item-list-tabs -->

<?php do_action('bp_after_directory_example_page'); ?>



<div class="offers myoffers">

    <?php locate_template(array('offers/offers-loop.php'), true); ?>

</div><!-- #offers-dir-list -->