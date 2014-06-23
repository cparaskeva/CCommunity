<?php

/**
 * The -functions.php file is a good place to store miscellaneous functions needed by current plugin.
 */

/**
 * bp_tools_facilities_load_template_filter()
 *
 * You can define a custom load template filter for your component. This will allow
 * you to store and load template files from your plugin directory.
 *
 * This will also allow users to override these templates in their active theme and
 * replace the ones that are stored in the plugin directory.
 *
 * If you're not interested in using template files, then you don't need this function.
 *
 * This will become clearer in the function bp_tools_facilities_screen_one() when you want to load
 * a template file.
 */
function bp_tools_facilities_load_template_filter($found_template, $templates) {
    global $bp;

    /**
     * Only filter the template location when we're on the example component pages.
     */
    if ($bp->current_component != $bp->tools_facilities->slug)
        return $found_template;

    foreach ((array) $templates as $template) {
        if (file_exists(STYLESHEETPATH . '/' . $template))
            $filtered_templates[] = STYLESHEETPATH . '/' . $template;
        else
            $filtered_templates[] = dirname(__FILE__) . '/templates/' . $template;
    }

    $found_template = $filtered_templates[0];

    return apply_filters('bp_tools_facilities_load_template_filter', $found_template);
}

add_filter('bp_located_template', 'bp_tools_facilities_load_template_filter', 10, 2);



/**
 * bp_tools_facilities_publish_tool_facility()
 *
 * Create a new tool_facility and store it to database. After insertion to 
 * the database the tool_facility is published to the Tool_Facilitys Directory so
 * every registered member to the CECommunity Platform can see it.
 * 
 */
function bp_tools_facilities_publish_tool_facility($tool_facility_args) {
    global $bp;

  
    /* Avoid duplicate entry in database ... */
    //check_admin_referer('bp_tools_facilities_publish_tool_facility');

    // Let's also record it in our custom database tables
    $tool_facility_new = new BP_Tool_Facility($tool_facility_args);
    return $tool_facility_new->save();

    /*
     * Now we've registered the new high-five, lets work on some notification and activity
     * stream magic.
     */

    /*
     * Post a screen notification to the user's notifications menu.
     * Remember, like activity streams we need to tell the activity stream component how to format
     * this notification in bp_tools_facilities_format_notifications() using the 'new_high_five' action.
     */
    //bp_core_add_notification($from_user_id, $to_user_id, $bp->tools_facilities->slug, 'new_high_five');

    /* Now record the new 'new_high_five' activity item */
    /* $to_user_link = bp_core_get_userlink($to_user_id);
      $from_user_link = bp_core_get_userlink($from_user_id);


      /* We'll use this do_action call to send the email notification. See bp-example-notifications.php */
    /* do_action('bp_tools_facilities_send_high_five', $to_user_id, $from_user_id);*/
}

/**
 * bp_tools_facilities_update_tool_facility()
 *
 * Update an already existed tool_facility to  the database.
 */
function bp_tools_facilities_update_tool_facility($tool_facility_args) {
    //Check if input a valid array
    if (!is_array($tool_facility_args))
        return false;
    global $bp;
    $tool_facility_to_update = $bp->tools_facilities->current_tool_facility;

    //If current_tool_facility is null of is not instance of BP_tool_facility do nothing
    if (!($tool_facility_to_update instanceof BP_Tool_Facility) || $tool_facility_to_update == null)
        return false;

    foreach ($tool_facility_args as $key => $value) {
        $tool_facility_to_update->$key = $value;
        //echo "Key: ".$key." Value: ".$value;        
    }

    //Save changes to DB
    return $tool_facility_to_update->save();
}



/* Count all the tools_facilities stored in DB */

function tools_facilities_get_total_tools_facilities_count() {
    if (!$count = wp_cache_get('bp_total_tools_facilities_count', 'bp')) {
        $count = BP_Tool_Facility::tools_facilities_get_total_tools_facilities_count();
        wp_cache_set('bp_total_tools_facilities_count', $count, 'bp');
    }
    return $count;
}

/* Count all the tools_facilities that a member owns */

function tools_facilities_total_tools_facilities_for_user($user_id = 0) {
global $bp;
if ($bp->current_action == "create-tool_facility" )
    return;

    if (empty($user_id))
        $user_id = ( bp_displayed_user_id() ) ? bp_displayed_user_id() : bp_loggedin_user_id();

    if (!$count = wp_cache_get('bp_total_tools_facilities_for_user_' . $user_id, 'bp')) {
        $count = BP_Tool_Facility::tools_facilities_total_tools_facilities_count($user_id);
        wp_cache_set('bp_total_tools_facilities_for_user_' . $user_id, $count, 'bp');
    }

    return $count;
}



/* Used to filter Tool_Facilitys Pages based on it catgory */
function bp_tools_facilities_current_category(){
     return ($_GET["tool_facility_type"].$_POST["tool_facility_type"] == "" ? "none" :$_GET["tool_facility_type"].$_POST["tool_facility_type"] );  
}

?>