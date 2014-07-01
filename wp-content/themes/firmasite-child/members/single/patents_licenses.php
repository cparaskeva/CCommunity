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
?>


<div class="item-list-tabs no-ajax" id="subnav" role="navigation">

    <ul class="nav nav-pills">
        <?php if (bp_is_my_profile()) bp_get_options_nav(); ?>

        <li id="patents_licenses-order-select" class="last pull-right filter">
            <label for="patents_licenses-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
            <select id="patents_licenses-order-by">
                <option <?php echo ($_COOKIE['bp-patents_licenses-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-patents_licenses-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-patents_licenses-filter'] == "offertype" ? "selected='selected'" : "") ?> value="offertype"><?php _e('Patent & License Type', 'firmasite'); ?></option> 
            </select>
        </li>
        <?php do_action('bp_offers_directory_example_filter'); ?>

    </ul>

</div><!-- .item-list-tabs -->

<div style="height:20px;"></div>

<div class="patents_licenses mypatents_licenses">

    <?php locate_template(array('patents_licenses/patents_licenses-loop.php'), true); ?>

</div><!-- #patents_licenses-dir-list -->