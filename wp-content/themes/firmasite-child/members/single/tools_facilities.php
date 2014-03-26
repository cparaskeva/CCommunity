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

        <li id="tools_facilities-order-select" class="last pull-right filter">
            <label for="tools_facilities-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
            <select id="tools_facilities-order-by">
                <option <?php echo ($_COOKIE['bp-tools_facilities-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-tools_facilities-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-tools_facilities-filter'] == "tool_facilitytype" ? "selected='selected'" : "") ?> value="offertype"><?php _e('Patent & License Type', 'firmasite'); ?></option> 
            </select>
        </li>
        <?php do_action('bp_offers_directory_example_filter'); ?>

    </ul>

</div><!-- .item-list-tabs -->

<div class="tools_facilities mytools_facilities">

    <?php locate_template(array('tools_facilities/tools_facilities-loop.php'), true); ?>

</div><!-- #tools_facilities-dir-list -->