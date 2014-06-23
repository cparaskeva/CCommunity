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

        <li id="alerts-order-select" class="last pull-right filter">
            <label for="alerts-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
            <select id="alerts-order-by">
                <option <?php echo ($_COOKIE['bp-alerts-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-alerts-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
            </select>
        </li>

    </ul>

</div><!-- .item-list-tabs -->

<div class="alerts myalerts">

    <?php locate_template(array('alerts/alerts-loop.php'), true); ?>

</div><!-- #alerts-dir-list -->