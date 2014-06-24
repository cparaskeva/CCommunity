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
function bp_challenges_directory_setup() {
    if (bp_is_challenge_component() && !bp_current_action() && !bp_current_item()) {
        // This wrapper function sets the $bp->is_directory flag to true, which help other
        // content to display content properly on your directory.
        bp_update_is_directory(true, 'challenges');

        // Add an action so that plugins can add content or modify behavior
        do_action('bp_challenges_directory_setup');
        bp_core_load_template(apply_filters('example_directory_template', 'challenges/index'));
    }
}

add_action('bp_screens', 'bp_challenges_directory_setup', 2);

/**
 * bp_challenges_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-one"
 */
function bp_challenges_screen_one() {
    global $bp;

    do_action('bp_challenges_screen_one');

    bp_core_load_template(apply_filters('bp_challenges_template_screen_one', 'members/single/home'));
}

/**
 * bp_challenges_create_challenge()
 *
 * Sets up and displays the screen output for the sub nav item "example/create-challenge"
 */
function bp_challenges_create_challenge() {
    global $bp;

    /**
     * On the output for this second screen, as an example, there are terms and conditions with an
     * "Accept" link (directs to http://example.org/members/andy/example/create-challenge/accept)
     * and a "Reject" link (directs to http://example.org/members/andy/example/create-challenge/reject)
     */
    if (bp_is_challenge_component() && bp_is_current_action('create-challenge')) {

        if (isset($_POST['challenge_submit'])) {
            //Check the nonce
            if (!check_admin_referer('challenges_create_challenge'))
                return false;

            $errors = false;

            $date = date_parse($_POST['challenge-deadline']);
            $date_val = false;
            if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
                $date_val = true;

            //Check if user has choosen an challenge type othen than "none"
            if (!($_POST['challenge-rights'] != "none" && strlen($_POST['challenge-title']) > 0 && strlen($_POST['challenge-description']) > 0) && !$date_val)
                bp_core_add_message(__('Error publishing your challenge. Please fill in all the required fields.', 'bp-patent-license'), 'error');
            else

            if (time() > strtotime($_POST['challenge-deadline'])) {
                bp_core_add_message(__('Error publishing your challenge. Invalid deadline date entered', 'bp-example'), 'error');
            } else if (!(intval($_POST['challenge-reward']) > 0)) {
                bp_core_add_message(__('Error publishing your challenge , reward field must be a positive numeric value... ', 'bp-example'), 'error');
            } else {
                //Validation Success Save the challenge to DB               
                $group_id = CECOM_Organization::getUserGroupID();
                $user_id = bp_current_user_id();

                $challenge_new = array(
                    'id' => 0,
                    'uid' => $user_id, //User ID
                    'gid' => $group_id, //Group ID
                    'title' => $_POST['challenge-title'],
                    'description' => $_POST['challenge-description'],
                    'deadline' => $_POST['challenge-deadline'],
                    'reward' => $_POST['challenge-reward'],
                    'right_id' => $_POST['challenge-rights'],
                    'date' => date('Y-m-d H:i:s'),
                    'sectors' => ( empty($_POST['challenge-sectors']) ? "null" : explode(",", $_POST['challenge-sectors']))
                );

                if (bp_challenges_publish_challenge($challenge_new))
                    bp_core_add_message(__('Your challenge has been succesfuly published!', 'bp-example'), 'success');
                else
                    bp_core_add_message(__('Unable to insert infromation to database..', 'bp-example'), 'error');
            }


            /**
             * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
             * just by refreshing the browser.
             */
            bp_core_redirect(bp_loggedin_user_domain() . bp_get_challenges_slug() . "/" . $bp->current_action);
        }


        /**
         * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
         * just by refreshing the browser.
         */
        //bp_core_redirect(bp_loggedin_user_domain() . bp_get_challenges_slug() . "/" . $bp->current_action);


        do_action('bp_challenges_create_challenge');

        /* Finally load the plugin template file. */
        bp_core_load_template(apply_filters('bp_core_template_plugin', 'challenges/create'));
    }
}

add_action('bp_screens', 'bp_challenges_create_challenge');

/**
 * The following screen functions are called when the Settings subpanel for this component is viewed
 */
function bp_challenges_screen_settings_menu() {
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

    add_action('bp_template_content_header', 'bp_challenges_screen_settings_menu_header');
    add_action('bp_template_title', 'bp_challenges_screen_settings_menu_title');
    add_action('bp_template_content', 'bp_challenges_screen_settings_menu_content');

    bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

function bp_challenges_screen_settings_menu_header() {
    _e('Example Settings Header', 'bp-example');
}

function bp_challenges_screen_settings_menu_title() {
    _e('Example Settings', 'bp-example');
}

function bp_challenges_screen_settings_menu_content() {
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

    function challenges_screen_challenge_admin_edit_details() {

        if ('edit-details' != bp_get_challenge_current_admin_tab())
            return false;


        if (!bp_is_item_admin())
            return false;

        if (isset($_POST['save'])) {

            //Check the nonce
            if (!check_admin_referer('challenges_edit_challenge_details'))
                return false;

            if (time() > strtotime($_POST['challenge-deadline'])) {
                bp_core_add_message(__('Error publishing your challenge. Invalid deadline date entered', 'bp-example'), 'error');
            }else

            if (!(intval($_POST['challenge-reward']) > 0)) {
                bp_core_add_message(__('Error updating your challenge , reward field must be a positive numeric value... ', 'bp-example'), 'error');
            } else {
                //Put the changes to an array
                $challenge_update = array(
                    'title' => $_POST['challenge-title'],
                    'description' => $_POST['challenge-description'],
                    'deadline' => $_POST['challenge-deadline'],
                    'reward' => $_POST['challenge-reward'],
                    'right_id' => $_POST['challenge-rights'],
                    'date' => date('Y-m-d H:i:s'),
                    'sectors' => ( empty($_POST['challenge-sectors']) ? "null" : explode(",", $_POST['challenge-sectors']))
                );

                if (bp_challenges_update_challenge($challenge_update))
                    bp_core_add_message(__('Your challenge has been succesfuly updated!', 'bp-example'), 'success');
                else
                    bp_core_add_message(__('Unable to update the current challenge...', 'bp-example'), 'error');
            }
            do_action('challenges_challenge_details_edited', $bp->challenges->current_challenge->id);

            bp_core_redirect(bp_challenge_get_permalink() . "admin/edit-details");
        }


        do_action('challenges_screen_challenge_admin_edit_details', $bp->challenges->current_challenge->id);

        bp_core_load_template(apply_filters('challenges_template_challenge_admin', 'challenges/single/home'));
    }

    add_action('bp_screens', 'challenges_screen_challenge_admin_edit_details');

    function challenges_screen_challenge_admin_delete_challenge() {

        if ('delete-challenge' != bp_get_challenge_current_admin_tab())
            return false;

        global $bp;

        if (bp_is_item_admin()) {

            if (isset($_REQUEST['delete-challenge-button']) && isset($_REQUEST['delete-challenge-understand'])) {

                // Check the nonce first.
                if (!check_admin_referer('challenges_delete_challenge')) {
                    return false;
                }


                // Patent_License admin has deleted the group, now do it. 
                if (!$bp->challenges->current_challenge->delete()) {
                    bp_core_add_message(__('There was an error deleting the challenge, please try again.', 'buddypress'), 'error');
                } else {
                    bp_core_add_message(__('The challenge was deleted successfully', 'buddypress'));

                    do_action('challenges_challenge_deleted', $bp->groups->current_group->id);

                    bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_challenges_slug()));
                }

                bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_challenges_slug()));
            }
        }
        do_action('challenges_screen_challenge_admin_delete_challenge', $bp->challenges->current_challenge->id);

        bp_core_load_template(apply_filters('challenges_template_challenge_admin_delete_challenge', 'challenges/single/home'));
    }

    add_action('bp_screens', 'challenges_screen_challenge_admin_delete_challenge');

    function challenges_screen_challenge_admin() {
        if (!bp_is_challenge_component() || !bp_is_current_action('admin'))
            return false;

        if (bp_action_variables())
            return false;

        bp_core_redirect('edit-details');
    }

    function challenges_screen_challenge_home() {

        if (!bp_is_single_item()) {
            return false;
        }

        bp_core_load_template(apply_filters('challenges_template_challenge_home', 'challenges/single/home'));
    }
    ?>



