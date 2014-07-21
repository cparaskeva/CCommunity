<?php
/* * ******************************************************************************
 * Screen Functions
 *
 * Screen functions are the controllers of BuddyPress. They will execute when their
 * specific URL is caught. They will first save or manipulate data using business
 * functions, then pass on the user to a template file.
 */

/**
 * If your component uses a top-level directory, this function will catch the requests and load
 * the index page.
 */
function bp_tools_facilities_directory_setup() {
    if (bp_is_tool_facility_component() && !bp_current_action() && !bp_current_item()) {
        // This wrapper function sets the $bp->is_directory flag to true, which help other
        // content to display content properly on your directory.
        bp_update_is_directory(true, 'tools_facilities');

        // Add an action so that plugins can add content or modify behavior
        do_action('bp_tools_facilities_directory_setup');
        bp_core_load_template(apply_filters('example_directory_template', 'tools_facilities/index'));
    }
}

add_action('bp_screens', 'bp_tools_facilities_directory_setup', 2);

/**
 * bp_tools_facilities_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-one"
 */
function bp_tools_facilities_screen_one() {
    global $bp;

    do_action('bp_tools_facilities_screen_one');

    bp_core_load_template(apply_filters('bp_tools_facilities_template_screen_one', 'members/single/home'));
}

/**
 * bp_tools_facilities_create_tool_facility()
 *
 * Sets up and displays the screen output for the sub nav item "example/create-tool_facility"
 */
function bp_tools_facilities_create_tool_facility() {
    global $bp;

    /**
     * On the output for this second screen, as an example, there are terms and conditions with an
     * "Accept" link (directs to http://example.org/members/andy/example/create-tool_facility/accept)
     * and a "Reject" link (directs to http://example.org/members/andy/example/create-tool_facility/reject)
     */
    if (bp_is_tool_facility_component() && bp_is_current_action('create-tool_facility')) {

        if (isset($_POST['tool_facility_submit'])) {
            //Check the nonce
            if (!check_admin_referer('tools_facilities_create_tool_facility'))
                return false;

            $errors = false;
            //Check if user has choosen an tool_facility type othen than "none"
            if (!($_POST['tool-facility-country'] != "" && strlen($_POST['tool-facility-location']) != "" && strlen($_POST['tool-facility-description']) > 0 && $_POST['tool-facility-exchange'] != "none" && $_POST['tool-facility-operation'] != "none"))
                bp_core_add_message(__('Error puplishing your tool/facility rent. Please fill in all the required fields.', 'bp-tool-facility'), 'error');
            else {
                //Validation Success Save the tool_facility to DB               
                $group_id = CECOM_Organization::getUserGroupID();
                $user_id = bp_current_user_id();

                $tool_facility_new = array(
                    'id' => 0,
                    'uid' => $user_id, //User ID
                    'gid' => $group_id, //Group ID
                    'description' => $_POST['tool-facility-description'],
                    'country_id' => $_POST['tool-facility-country'],
                    'location' => $_POST['tool-facility-location'],
                    'payment_id' => $_POST['tool-facility-payment'],
                    'operation_id' => $_POST['tool-facility-operation'],
                    'date' => date('Y-m-d H:i:s')
                );
                if (bp_tools_facilities_publish_tool_facility($tool_facility_new))
                    bp_core_add_message(__('Your tool/facility has been successfully published!', 'bp-example'), 'success');
                else
                    bp_core_add_message(__('Unable to insert information to database..', 'bp-example'), 'error');
            }


            /**
             * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
             * just by refreshing the browser.
             */
            bp_core_redirect(bp_loggedin_user_domain() . bp_get_tools_facilities_slug() . "/" . $bp->current_action);
        }


        /**
         * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
         * just by refreshing the browser.
         */
        //bp_core_redirect(bp_loggedin_user_domain() . bp_get_tools_facilities_slug() . "/" . $bp->current_action);


        do_action('bp_tools_facilities_create_tool_facility');

        /* Finally load the plugin template file. */
        bp_core_load_template(apply_filters('bp_core_template_plugin', 'tools_facilities/create'));
    }
}

add_action('bp_screens', 'bp_tools_facilities_create_tool_facility');

/**
 * The following screen functions are called when the Settings subpanel for this component is viewed
 */
function bp_tools_facilities_screen_settings_menu() {
    global $bp, $current_user, $bp_settings_updated, $pass_error;

    if (isset($_POST['submit'])) {
        /* Check the nonce */
        check_admin_referer('bp-example-admin');

        $bp_settings_updated = true;

        /**
         * This is when the user has hit the save button on their settings.
         * The best place to store these settings is in wp_usermeta.
         */
        update_user_meta($bp->loggedin_user->id, 'bp-example-option-one', attribute_escape($_POST['bp-example-option-one']));
    }

    add_action('bp_template_content_header', 'bp_tools_facilities_screen_settings_menu_header');
    add_action('bp_template_title', 'bp_tools_facilities_screen_settings_menu_title');
    add_action('bp_template_content', 'bp_tools_facilities_screen_settings_menu_content');

    bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

function bp_tools_facilities_screen_settings_menu_header() {
    _e('Example Settings Header', 'bp-example');
}

function bp_tools_facilities_screen_settings_menu_title() {
    _e('Example Settings', 'bp-example');
}

function bp_tools_facilities_screen_settings_menu_content() {
    global $bp, $bp_settings_updated;
    ?>
    <?php if ($bp_settings_updated) { ?>
        <div id="message" class="updated fade">
            <p><?php _e('Changes Saved.', 'bp-example') ?></p>
        </div>
    <?php } ?>
    <form action="<?php echo $bp->loggedin_user->domain . 'settings/example-admin'; ?>" name="bp-example-admin-form" id="account-delete-form" class="bp-example-admin-form" method="post">
        <input type="checkbox" name="bp-example-option-one" id="bp-example-option-one" value="1"<?php if ('1' == get_user_meta($bp->loggedin_user->id, 'bp-example-option-one', true)) : ?> checked="checked"<?php endif; ?> /> <?php _e('Do you love clicking checkboxes?', 'bp-example'); ?>
        <p class="submit">
            <input type="submit" value="<?php _e('Save Settings', 'bp-example') ?> &raquo;" id="submit" name="submit" />
        </p>
        <?php
        /* This is very important, don't leave it out. */
        wp_nonce_field('bp-example-admin');
        ?>
    </form>
    <?php
}

function tools_facilities_screen_tool_facility_admin_edit_details() {

    if ('edit-details' != bp_get_tool_facility_current_admin_tab())
        return false;


    if (!bp_is_item_admin())
        return false;

    if (isset($_POST['save'])) {

        //Check the nonce
        if (!check_admin_referer('tools_facilities_edit_tool_facility_details'))
            return false;
        //Set a default value if location is not specified
        if (empty($_POST['tool-facility-location']))
            $_POST['tool-facility-location']= "<i>unspecified</i>";
        
        //Put the changes to an array
        $tool_facility_update = array(
        'description' => $_POST['tool-facility-description'],
        'country_id' => $_POST['tool-facility-country'],
        'location' => $_POST['tool-facility-location'],
        'payment_id' => $_POST['tool-facility-payment'],
        'operation_id' => $_POST['tool-facility-operation'],
        'date' => date('Y-m-d H:i:s')
        );
        if (bp_tools_facilities_update_tool_facility($tool_facility_update))
            bp_core_add_message(__('Your tool/facility has been successfully updated!', 'bp-example'), 'success');
        else
            bp_core_add_message(__('Unable to update the current tool/facility...', 'bp-example'), 'error');

        do_action('tools_facilities_tool_facility_details_edited', $bp->tools_facilities->current_tool_facility->id);

        bp_core_redirect(bp_tool_facility_get_permalink() . "admin/edit-details");
    }


    do_action('tools_facilities_screen_tool_facility_admin_edit_details', $bp->tools_facilities->current_tool_facility->id);

    bp_core_load_template(apply_filters('tools_facilities_template_tool_facility_admin', 'tools_facilities/single/home'));
}

add_action('bp_screens', 'tools_facilities_screen_tool_facility_admin_edit_details');

function tools_facilities_screen_tool_facility_admin_delete_tool_facility() {

    if ('delete-tool_facility' != bp_get_tool_facility_current_admin_tab())
        return false;

    global $bp;

    if (bp_is_item_admin()) {

        if (isset($_REQUEST['delete-tool_facility-button']) && isset($_REQUEST['delete-tool_facility-understand'])) {

            // Check the nonce first.
            if (!check_admin_referer('tools_facilities_delete_tool_facility')) {
                return false;
            }


            // Tool_Facility admin has deleted the group, now do it. 
            if (!$bp->tools_facilities->current_tool_facility->delete()) {
                bp_core_add_message(__('There was an error deleting the tool_facility, please try again.', 'buddypress'), 'error');
            } else {
                bp_core_add_message(__('The tool_facility was deleted successfully', 'buddypress'));

                do_action('tools_facilities_tool_facility_deleted', $bp->groups->current_group->id);

                bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_tools_facilities_slug()));
            }

            bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_tools_facilities_slug()));
        }
    }
    do_action('tools_facilities_screen_tool_facility_admin_delete_tool_facility', $bp->tools_facilities->current_tool_facility->id);

    bp_core_load_template(apply_filters('tools_facilities_template_tool_facility_admin_delete_tool_facility', 'tools_facilities/single/home'));
}

add_action('bp_screens', 'tools_facilities_screen_tool_facility_admin_delete_tool_facility');

function tools_facilities_screen_tool_facility_admin() {
    if (!bp_is_tool_facility_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_action_variables())
        return false;

    bp_core_redirect('edit-details');
}

function tools_facilities_screen_tool_facility_home() {

    if (!bp_is_single_item()) {
        return false;
    }

    bp_core_load_template(apply_filters('tools_facilities_template_tool_facility_home', 'tools_facilities/single/home'));
}
?>



