<?php

/**
 * The -functions.php file is a good place to store miscellaneous functions needed by current plugin.
 */

/**
 * bp_offers_load_template_filter()
 *
 * You can define a custom load template filter for your component. This will allow
 * you to store and load template files from your plugin directory.
 *
 * This will also allow users to override these templates in their active theme and
 * replace the ones that are stored in the plugin directory.
 *
 * If you're not interested in using template files, then you don't need this function.
 *
 * This will become clearer in the function bp_offers_screen_one() when you want to load
 * a template file.
 */
function bp_offers_load_template_filter($found_template, $templates) {
    global $bp;

    /**
     * Only filter the template location when we're on the example component pages.
     */
    if ($bp->current_component != $bp->offers->slug)
        return $found_template;

    foreach ((array) $templates as $template) {
        if (file_exists(STYLESHEETPATH . '/' . $template))
            $filtered_templates[] = STYLESHEETPATH . '/' . $template;
        else
            $filtered_templates[] = dirname(__FILE__) . '/templates/' . $template;
    }

    $found_template = $filtered_templates[0];

    return apply_filters('bp_offers_load_template_filter', $found_template);
}

add_filter('bp_located_template', 'bp_offers_load_template_filter', 10, 2);



/**
 * bp_offers_publish_offer()
 *
 * Create a new offer and store it to database. After insertion to 
 * the database the offer is published to the Offers Directory so
 * every registered member to the CECommunity Platform can see it.
 * 
 */
function bp_offers_publish_offer($offer_args) {
    global $bp;

  
    /* Avoid duplicate entry in database ... */
    //check_admin_referer('bp_offers_publish_offer');

    // Let's also record it in our custom database tables
    $offer_new = new BP_Offer($offer_args);
    return $offer_new->save();

    /*
     * Now we've registered the new high-five, lets work on some notification and activity
     * stream magic.
     */

    /*
     * Post a screen notification to the user's notifications menu.
     * Remember, like activity streams we need to tell the activity stream component how to format
     * this notification in bp_offers_format_notifications() using the 'new_high_five' action.
     */
    //bp_core_add_notification($from_user_id, $to_user_id, $bp->offers->slug, 'new_high_five');

    /* Now record the new 'new_high_five' activity item */
    /* $to_user_link = bp_core_get_userlink($to_user_id);
      $from_user_link = bp_core_get_userlink($from_user_id);


      /* We'll use this do_action call to send the email notification. See bp-example-notifications.php */
    /* do_action('bp_offers_send_high_five', $to_user_id, $from_user_id);*/
}

/**
 * bp_offers_update_offer()
 *
 * Update an already existed offer to  the database.
 */
function bp_offers_update_offer($offer_args) {

    //Check if input a valid array
    if (!is_array($offer_args))
        return false;
    global $bp;
    $offer_to_update = $bp->offers->current_offer;

    //If current_offer is null of is not instance of BP_offer do nothing
    if (!($offer_to_update instanceof BP_Offer) || $offer_to_update == null)
        return false;

    foreach ($offer_args as $key => $value) {
        $offer_to_update->$key = $value;
        //echo "Key: ".$key." Value: ".$value;        
    }

    //Save changes to DB
    return $offer_to_update->save();
}



/* Count all the offers stored in DB */

function offers_get_total_offers_count() {
    if (!$count = wp_cache_get('bp_total_offers_count', 'bp')) {
        $count = BP_Offer::offers_get_total_offers_count();
        wp_cache_set('bp_total_offers_count', $count, 'bp');
    }
    return $count;
}

/* Count all the offers that a member owns */

function offers_total_offers_for_user($user_id = 0) {

    if (empty($user_id))
        $user_id = ( bp_displayed_user_id() ) ? bp_displayed_user_id() : bp_loggedin_user_id();

    if (!$count = wp_cache_get('bp_total_offers_for_user_' . $user_id, 'bp')) {
        $count = BP_Offer::offers_total_offers_count($user_id);
        wp_cache_set('bp_total_offers_for_user_' . $user_id, $count, 'bp');
    }

    return $count;
}



/* Used to filter Offers Pages based on it catgory */
function bp_offers_current_catgory(){
     return ($_GET["offer_type"].$_POST["offer_type"] == "" ? "none" :$_GET["offer_type"].$_POST["offer_type"] );  
}

?>