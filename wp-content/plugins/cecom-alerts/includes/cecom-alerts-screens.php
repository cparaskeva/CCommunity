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
function bp_alerts_directory_setup() {
    if (bp_is_alert_component() && !bp_current_action() && !bp_current_item()) {
        // This wrapper function sets the $bp->is_directory flag to true, which help other
        // content to display content properly on your directory.
        bp_update_is_directory(true, 'alerts');

        // Add an action so that plugins can add content or modify behavior
        do_action('bp_alerts_directory_setup');
        bp_core_load_template(apply_filters('alerts_directory_template', 'alerts/index'));
    }
}

add_action('bp_screens', 'bp_alerts_directory_setup', 2);

/**
 * bp_alerts_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-one"
 */
function bp_alerts_screen_one() {
    global $bp;

    do_action('bp_alerts_screen_one');

    bp_core_load_template(apply_filters('bp_alerts_template_screen_one', 'members/single/home'));
}

/**
 * bp_alerts_create_alert()
 *
 * Sets up and displays the screen output for the sub nav item "example/create-alert"
 */
function bp_alerts_create_alert() {
    global $bp;

    /**
     * On the output for this second screen, as an example, there are terms and conditions with an
     * "Accept" link (directs to http://example.org/members/andy/example/create-alert/accept)
     * and a "Reject" link (directs to http://example.org/members/andy/example/create-alert/reject)
     */
    if (bp_is_alert_component() && bp_is_current_action('create-alert')) {

        if (isset($_POST['alert_submit'])) {
            //Check the nonce
            if (!check_admin_referer('alerts_create_alert'))
                return false;

            $errors = false;
            //Check if user has choosen an alert type othen than "none"
            if (!($_POST['tool-facility-country'] != "" && strlen($_POST['tool-facility-location']) != "" && strlen($_POST['tool-facility-description']) > 0 && $_POST['tool-facility-exchange'] != "none" && $_POST['tool-facility-operation'] != "none"))
                bp_core_add_message(__('Error puplishing your tool/facility rent. Please fill in all the required fields.', 'bp-tool-facility'), 'error');
            else {
                //Validation Success Save the alert to DB               
                $group_id = CECOM_Organization::getUserGroupID();
                $user_id = bp_current_user_id();

                $alert_new = array(
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
                if (bp_alerts_publish_alert($alert_new))
                    bp_core_add_message(__('Your tool/facility has been succesfuly published!', 'bp-example'), 'success');
                else
                    bp_core_add_message(__('Unable to insert infromation to database..', 'bp-example'), 'error');
            }


            /**
             * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
             * just by refreshing the browser.
             */
            bp_core_redirect(bp_loggedin_user_domain() . bp_get_alerts_slug() . "/" . $bp->current_action);
        }


        /**
         * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
         * just by refreshing the browser.
         */
        //bp_core_redirect(bp_loggedin_user_domain() . bp_get_alerts_slug() . "/" . $bp->current_action);


        do_action('bp_alerts_create_alert');

        /* Finally load the plugin template file. */
        bp_core_load_template(apply_filters('bp_core_template_plugin', 'alerts/create'));
    }
}

add_action('bp_screens', 'bp_alerts_create_alert');

/**
 * The following screen functions are called when the Settings subpanel for this component is viewed
 */
function bp_alerts_screen_settings_menu() {
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

    add_action('bp_template_content_header', 'bp_alerts_screen_settings_menu_header');
    add_action('bp_template_title', 'bp_alerts_screen_settings_menu_title');
    add_action('bp_template_content', 'bp_alerts_screen_settings_menu_content');

    bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

function bp_alerts_screen_settings_menu_header() {
    _e('Example Settings Header', 'bp-example');
}

function bp_alerts_screen_settings_menu_title() {
    _e('Example Settings', 'bp-example');
}

function bp_alerts_screen_settings_menu_content() {
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

function alerts_screen_alert_admin_edit_details() {

    if ('edit-details' != bp_get_alert_current_admin_tab())
        return false;


    if (!bp_is_item_admin())
        return false;

    if (isset($_POST['save'])) {

        //Check the nonce
        if (!check_admin_referer('alerts_edit_alert_details'))
            return false;
        //Set a default value if location is not specified
        if (empty($_POST['tool-facility-location']))
            $_POST['tool-facility-location']= "<i>unspecified</i>";
        
        //Put the changes to an array
        $alert_update = array(
        'description' => $_POST['tool-facility-description'],
        'country_id' => $_POST['tool-facility-country'],
        'location' => $_POST['tool-facility-location'],
        'payment_id' => $_POST['tool-facility-payment'],
        'operation_id' => $_POST['tool-facility-operation'],
        'date' => date('Y-m-d H:i:s')
        );
        if (bp_alerts_update_alert($alert_update))
            bp_core_add_message(__('Your tool/facility has been succesfuly updated!', 'bp-example'), 'success');
        else
            bp_core_add_message(__('Unable to update the current tool/facility...', 'bp-example'), 'error');

        do_action('alerts_alert_details_edited', $bp->alerts->current_alert->id);

        bp_core_redirect(bp_alert_get_permalink() . "admin/edit-details");
    }


    do_action('alerts_screen_alert_admin_edit_details', $bp->alerts->current_alert->id);

    bp_core_load_template(apply_filters('alerts_template_alert_admin', 'alerts/single/home'));
}

add_action('bp_screens', 'alerts_screen_alert_admin_edit_details');

function alerts_screen_alert_admin_delete_alert() {

    if ('delete-alert' != bp_get_alert_current_admin_tab())
        return false;

    global $bp;

    if (bp_is_item_admin()) {

        if (isset($_REQUEST['delete-alert-button']) && isset($_REQUEST['delete-alert-understand'])) {

            // Check the nonce first.
            if (!check_admin_referer('alerts_delete_alert')) {
                return false;
            }


            // Tool_Facility admin has deleted the group, now do it. 
            if (!$bp->alerts->current_alert->delete()) {
                bp_core_add_message(__('There was an error deleting the alert, please try again.', 'buddypress'), 'error');
            } else {
                bp_core_add_message(__('The alert was deleted successfully', 'buddypress'));

                do_action('alerts_alert_deleted', $bp->groups->current_group->id);

                bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_alerts_slug()));
            }

            bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_alerts_slug()));
        }
    }
    do_action('alerts_screen_alert_admin_delete_alert', $bp->alerts->current_alert->id);

    bp_core_load_template(apply_filters('alerts_template_alert_admin_delete_alert', 'alerts/single/home'));
}

add_action('bp_screens', 'alerts_screen_alert_admin_delete_alert');

function alerts_screen_alert_admin() {
    if (!bp_is_alert_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_action_variables())
        return false;

    bp_core_redirect('edit-details');
}

function alerts_screen_alert_home() {

    if (!bp_is_single_item()) {
        return false;
    }

    bp_core_load_template(apply_filters('alerts_template_alert_home', 'alerts/single/home'));
}
?>



