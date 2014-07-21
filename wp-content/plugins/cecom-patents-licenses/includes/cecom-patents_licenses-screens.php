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
function bp_patents_licenses_directory_setup() {
    if (bp_is_patent_license_component() && !bp_current_action() && !bp_current_item()) {
        // This wrapper function sets the $bp->is_directory flag to true, which help other
        // content to display content properly on your directory.
        bp_update_is_directory(true, 'patents_licenses');

        // Add an action so that plugins can add content or modify behavior
        do_action('bp_patents_licenses_directory_setup');
        bp_core_load_template(apply_filters('example_directory_template', 'patents_licenses/index'));
    }
}

add_action('bp_screens', 'bp_patents_licenses_directory_setup', 2);

/**
 * bp_patents_licenses_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-one"
 */
function bp_patents_licenses_screen_one() {
    global $bp;

    do_action('bp_patents_licenses_screen_one');

    bp_core_load_template(apply_filters('bp_patents_licenses_template_screen_one', 'members/single/home'));
}

/**
 * bp_patents_licenses_create_patent_license()
 *
 * Sets up and displays the screen output for the sub nav item "example/create-patent_license"
 */
function bp_patents_licenses_create_patent_license() {
    global $bp;

    /**
     * On the output for this second screen, as an example, there are terms and conditions with an
     * "Accept" link (directs to http://example.org/members/andy/example/create-patent_license/accept)
     * and a "Reject" link (directs to http://example.org/members/andy/example/create-patent_license/reject)
     */
    if (bp_is_patent_license_component() && bp_is_current_action('create-patent_license')) {

        if (isset($_POST['patent_license_submit'])) {
            //Check the nonce
            if (!check_admin_referer('patents_licenses_create_patent_license'))
                return false;

            $errors = false;
            //Check if user has choosen an patent_license type othen than "none"
            if (!(($_POST['patent-license-type'] != "none" && $_POST['patent-license-exchange'] != "none") && strlen($_POST['patent-license-description']) > 0 && $_POST['patent-license-countries'] != "none" ))
                bp_core_add_message(__('Error puplishing your patent/license offer. Please fill in all the required fields.', 'bp-patent-license'), 'error');
            else {
                //Validation Success Save the patent_license to DB               
                $group_id = CECOM_Organization::getUserGroupID();
                $user_id = bp_current_user_id();

                $patent_license_new = array(
                    'id' => 0,
                    'uid' => $user_id, //User ID
                    'gid' => $group_id, //Group ID
                    'type_id' => $_POST['patent-license-type'], //Patent_License type ID
                    'description' => $_POST['patent-license-description'],
                    'country_id' => $_POST['patent-license-countries'],
                    'exchange_id' => $_POST['patent-license-exchange'],
                    'date' => date('Y-m-d H:i:s'),
                    'sectors' => ( empty($_POST['patent-license-sectors']) ? "null" : explode(",", $_POST['patent-license-sectors'])),
                    'subsectors' => ( empty($_POST['patent-license-subsectors']) ? "null" : explode(",", $_POST['patent-license-subsectors'])
                    )
                );
                if (bp_patents_licenses_publish_patent_license($patent_license_new))
                    bp_core_add_message(__('Your patent/license has been successfully published!', 'bp-example'), 'success');
                else
                    bp_core_add_message(__('Unable to insert information to database..', 'bp-example'), 'error');
            }


            /**
             * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
             * just by refreshing the browser.
             */
            bp_core_redirect(bp_loggedin_user_domain() . bp_get_patents_licenses_slug() . "/" . $bp->current_action);
        }


        /**
         * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
         * just by refreshing the browser.
         */
        //bp_core_redirect(bp_loggedin_user_domain() . bp_get_patents_licenses_slug() . "/" . $bp->current_action);


        do_action('bp_patents_licenses_create_patent_license');

        /* Finally load the plugin template file. */
        bp_core_load_template(apply_filters('bp_core_template_plugin', 'patents_licenses/create'));
    }
}

add_action('bp_screens', 'bp_patents_licenses_create_patent_license');

/**
 * The following screen functions are called when the Settings subpanel for this component is viewed
 */
function bp_patents_licenses_screen_settings_menu() {
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

    add_action('bp_template_content_header', 'bp_patents_licenses_screen_settings_menu_header');
    add_action('bp_template_title', 'bp_patents_licenses_screen_settings_menu_title');
    add_action('bp_template_content', 'bp_patents_licenses_screen_settings_menu_content');

    bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

function bp_patents_licenses_screen_settings_menu_header() {
    _e('Example Settings Header', 'bp-example');
}

function bp_patents_licenses_screen_settings_menu_title() {
    _e('Example Settings', 'bp-example');
}

function bp_patents_licenses_screen_settings_menu_content() {
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

function patents_licenses_screen_patent_license_admin_edit_details() {

    if ('edit-details' != bp_get_patent_license_current_admin_tab())
        return false;


    if (!bp_is_item_admin())
        return false;

    if (isset($_POST['save'])) {

        //Check the nonce
        if (!check_admin_referer('patents_licenses_edit_patent_license_details'))
            return false;

        //Put the changes to an array
        $patent_license_update = array(
            'type_id' => $_POST['patent-license-type'], //Patent_License type ID
            'description' => $_POST['patent-license-description'],
            'country_id' => $_POST['patent-license-countries'],
            'exchange_id' => $_POST['patent-license-exchange'],
            'date' => date('Y-m-d H:i:s'),
            'sectors' => ( empty($_POST['patent-license-sectors']) ? "null" : explode(",", $_POST['patent-license-sectors'])),
            'subsectors' => ( empty($_POST['patent-license-subsectors']) ? "null" : explode(",", $_POST['patent-license-subsectors'])
            )
        );
        if (bp_patents_licenses_update_patent_license($patent_license_update))
            bp_core_add_message(__('Your patent/license has been successfully updated!', 'bp-example'), 'success');
        else
            bp_core_add_message(__('Unable to update the current patent/license...', 'bp-example'), 'error');

        do_action('patents_licenses_patent_license_details_edited', $bp->patents_licenses->current_patent_license->id);

        bp_core_redirect(bp_patent_license_get_permalink() . "admin/edit-details");
    }


    do_action('patents_licenses_screen_patent_license_admin_edit_details', $bp->patents_licenses->current_patent_license->id);

    bp_core_load_template(apply_filters('patents_licenses_template_patent_license_admin', 'patents_licenses/single/home'));
}

add_action('bp_screens', 'patents_licenses_screen_patent_license_admin_edit_details');

function patents_licenses_screen_patent_license_admin_delete_patent_license() {

    if ('delete-patent_license' != bp_get_patent_license_current_admin_tab())
        return false;

    global $bp;

    if (bp_is_item_admin()) {

        if (isset($_REQUEST['delete-patent_license-button']) && isset($_REQUEST['delete-patent_license-understand'])) {

            // Check the nonce first.
            if (!check_admin_referer('patents_licenses_delete_patent_license')) {
                return false;
            }


            // Patent_License admin has deleted the group, now do it. 
            if (!$bp->patents_licenses->current_patent_license->delete()) {
                bp_core_add_message(__('There was an error deleting the patent_license, please try again.', 'buddypress'), 'error');
            } else {
                bp_core_add_message(__('The patent_license was deleted successfully', 'buddypress'));

                do_action('patents_licenses_patent_license_deleted', $bp->groups->current_group->id);

                bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_patents_licenses_slug()));
            }

            bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_patents_licenses_slug()));
        }
    }
    do_action('patents_licenses_screen_patent_license_admin_delete_patent_license', $bp->patents_licenses->current_patent_license->id);

    bp_core_load_template(apply_filters('patents_licenses_template_patent_license_admin_delete_patent_license', 'patents_licenses/single/home'));
}

add_action('bp_screens', 'patents_licenses_screen_patent_license_admin_delete_patent_license');

function patents_licenses_screen_patent_license_admin() {
    if (!bp_is_patent_license_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_action_variables())
        return false;

    bp_core_redirect('edit-details');
}

function patents_licenses_screen_patent_license_home() {

    if (!bp_is_single_item()) {
        return false;
    }

    bp_core_load_template(apply_filters('patents_licenses_template_patent_license_home', 'patents_licenses/single/home'));
}
?>



