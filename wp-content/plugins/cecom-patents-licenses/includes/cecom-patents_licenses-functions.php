<?php

/**
 * The -functions.php file is a good place to store miscellaneous functions needed by current plugin.
 */

/**
 * bp_patents_licenses_load_template_filter()
 *
 * You can define a custom load template filter for your component. This will allow
 * you to store and load template files from your plugin directory.
 *
 * This will also allow users to override these templates in their active theme and
 * replace the ones that are stored in the plugin directory.
 *
 * If you're not interested in using template files, then you don't need this function.
 *
 * This will become clearer in the function bp_patents_licenses_screen_one() when you want to load
 * a template file.
 */
function bp_patents_licenses_load_template_filter($found_template, $templates) {
    global $bp;

    /**
     * Only filter the template location when we're on the example component pages.
     */
    if ($bp->current_component != $bp->patents_licenses->slug)
        return $found_template;

    foreach ((array) $templates as $template) {
        if (file_exists(STYLESHEETPATH . '/' . $template))
            $filtered_templates[] = STYLESHEETPATH . '/' . $template;
        else
            $filtered_templates[] = dirname(__FILE__) . '/templates/' . $template;
    }

    $found_template = $filtered_templates[0];

    return apply_filters('bp_patents_licenses_load_template_filter', $found_template);
}

add_filter('bp_located_template', 'bp_patents_licenses_load_template_filter', 10, 2);



/**
 * bp_patents_licenses_publish_patent_license()
 *
 * Create a new patent_license and store it to database. After insertion to 
 * the database the patent_license is published to the Patent_Licenses Directory so
 * every registered member to the CECommunity Platform can see it.
 * 
 */
function bp_patents_licenses_publish_patent_license($patent_license_args) {
    global $bp;

  
    /* Avoid duplicate entry in database ... */
    //check_admin_referer('bp_patents_licenses_publish_patent_license');

    // Let's also record it in our custom database tables
    $patent_license_new = new BP_Patent_License($patent_license_args);
    return $patent_license_new->save();

    /*
     * Now we've registered the new high-five, lets work on some notification and activity
     * stream magic.
     */

    /*
     * Post a screen notification to the user's notifications menu.
     * Remember, like activity streams we need to tell the activity stream component how to format
     * this notification in bp_patents_licenses_format_notifications() using the 'new_high_five' action.
     */
    //bp_core_add_notification($from_user_id, $to_user_id, $bp->patents_licenses->slug, 'new_high_five');

    /* Now record the new 'new_high_five' activity item */
    /* $to_user_link = bp_core_get_userlink($to_user_id);
      $from_user_link = bp_core_get_userlink($from_user_id);


      /* We'll use this do_action call to send the email notification. See bp-example-notifications.php */
    /* do_action('bp_patents_licenses_send_high_five', $to_user_id, $from_user_id);*/
}

/**
 * bp_patents_licenses_update_patent_license()
 *
 * Update an already existed patent_license to  the database.
 */
function bp_patents_licenses_update_patent_license($patent_license_args) {
    //Check if input a valid array
    if (!is_array($patent_license_args))
        return false;
    global $bp;
    $patent_license_to_update = $bp->patents_licenses->current_patent_license;

    //If current_patent_license is null of is not instance of BP_patent_license do nothing
    if (!($patent_license_to_update instanceof BP_Patent_License) || $patent_license_to_update == null)
        return false;

    foreach ($patent_license_args as $key => $value) {
        $patent_license_to_update->$key = $value;
        //echo "Key: ".$key." Value: ".$value;        
    }

    //Save changes to DB
    return $patent_license_to_update->save();
}



/* Count all the patents_licenses stored in DB */

function patents_licenses_get_total_patents_licenses_count() {
    if (!$count = wp_cache_get('bp_total_patents_licenses_count', 'bp')) {
        $count = BP_Patent_License::patents_licenses_get_total_patents_licenses_count();
        wp_cache_set('bp_total_patents_licenses_count', $count, 'bp');
    }
    return $count;
}

/* Count all the patents_licenses that a member owns */

function patents_licenses_total_patents_licenses_for_user($user_id = 0) {
global $bp;
if ($bp->current_action == "create-patent_license" )
    return;

    if (empty($user_id))
        $user_id = ( bp_displayed_user_id() ) ? bp_displayed_user_id() : bp_loggedin_user_id();

    if (!$count = wp_cache_get('bp_total_patents_licenses_for_user_' . $user_id, 'bp')) {
        $count = BP_Patent_License::patents_licenses_total_patents_licenses_count($user_id);
        wp_cache_set('bp_total_patents_licenses_for_user_' . $user_id, $count, 'bp');
    }

    return $count;
}



/* Used to filter Patent_Licenses Pages based on it catgory */
function bp_patents_licenses_current_category(){
     return ($_GET["patent_license_type"].$_POST["patent_license_type"] == "" ? "none" :$_GET["patent_license_type"].$_POST["patent_license_type"] );  
}

?>