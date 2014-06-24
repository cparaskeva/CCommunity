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

        <li id="challenges-order-select" class="last pull-right filter">
            <label for="challenges-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
            <select id="challenges-order-by">
                <option <?php echo ($_COOKIE['bp-challenges-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-challenges-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
                <option <?php echo ($_COOKIE['bp-challenges-filter'] == "offertype" ? "selected='selected'" : "") ?> value="offertype"><?php _e('Patent & License Type', 'firmasite'); ?></option> 
            </select>
        </li>
    </ul>

</div><!-- .item-list-tabs -->

<div class="challenges mychallenges">

    <?php locate_template(array('challenges/challenges-loop.php'), true); ?>

</div><!-- #challenges-dir-list -->