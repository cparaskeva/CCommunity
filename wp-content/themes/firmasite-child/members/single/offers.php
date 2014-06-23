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

        <li id="offers-order-select" class="last pull-right filter">
            <label for="offers-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
            <select id="offers-order-by">
                <option <?php echo ($_COOKIE['bp-offers-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-offers-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-offers-filter'] == "offertype" ? "selected='selected'" : "") ?> value="offertype"><?php _e('Offer Type', 'firmasite'); ?></option> 
            </select>
        </li>
        <?php do_action('bp_offers_directory_example_filter'); ?>

    </ul>

</div><!-- .item-list-tabs -->

<div class="offers myoffers">

    <?php locate_template(array('offers/offers-loop.php'), true); ?>

</div><!-- #offers-dir-list -->