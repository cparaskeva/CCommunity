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
 *
 * @package BuddyPress_Template_Pack
 * @since 1.6
 */
function bp_offers_directory_setup() {
    if (bp_is_offer_component() && !bp_current_action() && !bp_current_item()) {
        // This wrapper function sets the $bp->is_directory flag to true, which help other
        // content to display content properly on your directory.
        bp_update_is_directory(true, 'offers');

        // Add an action so that plugins can add content or modify behavior
        do_action('bp_offers_directory_setup');
        bp_core_load_template(apply_filters('example_directory_template', 'offers/index'));
    }
}

add_action('bp_screens', 'bp_offers_directory_setup', 2);

/**
 * bp_offers_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-one"
 */
function bp_offers_screen_one() {
    global $bp;

    do_action('bp_offers_screen_one');

    bp_core_load_template(apply_filters('bp_offers_template_screen_one', 'members/single/home'));
}

/**
 * bp_offers_create_offer()
 *
 * Sets up and displays the screen output for the sub nav item "example/create-offer"
 */
function bp_offers_create_offer() {
    global $bp;

    /**
     * On the output for this second screen, as an example, there are terms and conditions with an
     * "Accept" link (directs to http://example.org/members/andy/example/create-offer/accept)
     * and a "Reject" link (directs to http://example.org/members/andy/example/create-offer/reject)
     */
    if (bp_is_offer_component() && bp_is_current_action('create-offer')) {

        //On submit
        if (isset($_POST['offer-type']) && $_POST['offer-type'] != "none") {
            $errors = false;
            //Check if user has choosen an offer type othen than "none"
            if ($_POST['collaboration-type'] == "none" || strlen($_POST['collaboration-description']) < 1 ||
                    (($_POST['collaboration-partner-sought'] == none ) && ($_POST['collaboration-programs'] == 'none')))
                bp_core_add_message(__('Error puplishing your proposal. Please fill in all the required fields.', 'bp-example'), 'error');
            else {
                //Validation Success Save the offer to DB               
                $group_id = CECOM_Organization::getUserGroupID();
                $user_id = bp_current_user_id();

                $offer_new = array(
                    'id' => 0,
                    'uid' => $user_id, //User ID
                    'gid' => $group_id, //Group ID
                    'type_id' => $_POST['offer-type'], //Offer type ID
                    'collaboration_id' => $_POST['collaboration-type'],
                    'description' => $_POST['collaboration-description'],
                    'partner_type_id' => $_POST['collaboration-partner-sought'],
                    //'country_id' => $_POST['collaboration-countries'],
                    'program_id' => $_POST['collaboration-programs'],
                    'date' => date('Y-m-d H:i:s')
                );

                if (bp_offers_publish_offer($offer_new))
                    bp_core_add_message(__('Your offer has been succesfuly published!', 'bp-example'), 'success');
                else
                    bp_core_add_message(__('Unable to insert infromation to database..', 'bp-example'), 'error');
            }


            /**
             * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
             * just by refreshing the browser.
             */
            // bp_core_redirect(bp_loggedin_user_domain() . bp_get_offers_slug() . "/" . $bp->current_action);
        }


        /**
         * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
         * just by refreshing the browser.
         */
        // bp_core_redirect( bp_loggedin_user_domain() . bp_get_offers_slug() );
    }


    do_action('bp_offers_create_offer');

    /* Finally load the plugin template file. */
    bp_core_load_template(apply_filters('bp_core_template_plugin', 'offers/create'));
}

/**
 * The following screen functions are called when the Settings subpanel for this component is viewed
 */
function bp_offers_screen_settings_menu() {
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

    add_action('bp_template_content_header', 'bp_offers_screen_settings_menu_header');
    add_action('bp_template_title', 'bp_offers_screen_settings_menu_title');
    add_action('bp_template_content', 'bp_offers_screen_settings_menu_content');

    bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

function bp_offers_screen_settings_menu_header() {
    _e('Example Settings Header', 'bp-example');
}

function bp_offers_screen_settings_menu_title() {
    _e('Example Settings', 'bp-example');
}

function bp_offers_screen_settings_menu_content() {
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

function offers_screen_offer_admin_edit_details() {

    if ('edit-details' != bp_get_offer_current_admin_tab())
        return false;


    if (!bp_is_item_admin())
        return false;

    if (isset($_POST['save'])) {

        //Check the nonce
        if (!check_admin_referer('offers_edit_offer_details'))
            return false;

        //Put the changes to an array
        $offer_update = array(
            'collaboration_id' => $_POST['collaboration-type'],
            'description' => $_POST['collaboration-description'],
            'partner_type_id' => $_POST['collaboration-partner-sought'],
            //'country_id' => $_POST['collaboration-countries'],
            'program_id' => $_POST['collaboration-programs'],
            'date' => date('Y-m-d H:i:s')
        );

        if (bp_offers_update_offer($offer_update))
            bp_core_add_message(__('Your offer has been succesfuly updated!', 'bp-example'), 'success');
        else
            bp_core_add_message(__('Unable to update the current offer...', 'bp-example'), 'error');

        do_action('offers_offer_details_edited', $bp->offers->current_offer->id);

        bp_core_redirect(bp_offer_get_permalink() . "admin/edit-details");
    }


    do_action('offers_screen_offer_admin_edit_details', $bp->offers->current_offer->id);

    bp_core_load_template(apply_filters('offers_template_offer_admin', 'offers/single/home'));
}

add_action('bp_screens', 'offers_screen_offer_admin_edit_details');

function offers_screen_offer_admin_delete_offer() {

    if ('delete-offer' != bp_get_offer_current_admin_tab())
        return false;

    global $bp;

    if (bp_is_item_admin()) {

        if (isset($_REQUEST['delete-offer-button']) && isset($_REQUEST['delete-offer-understand'])) {

            // Check the nonce first.
            if (!check_admin_referer('offers_delete_offer')) {
                return false;
            }


            // Offer admin has deleted the group, now do it. 
            if (!$bp->offers->current_offer->delete()) {
                bp_core_add_message(__('There was an error deleting the offer, please try again.', 'buddypress'), 'error');
            } else {
                bp_core_add_message(__('The offer was deleted successfully', 'buddypress'));

                do_action('offers_offer_deleted', $bp->groups->current_group->id);

                bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_offers_slug()));
            }

            bp_core_redirect(trailingslashit(bp_loggedin_user_domain() . bp_get_offers_slug()));
        }
    }
    do_action('offers_screen_offer_admin_delete_offer', $bp->offers->current_offer->id);

    bp_core_load_template(apply_filters('offers_template_offer_admin_delete_offer', 'offers/single/home'));
}

add_action('bp_screens', 'offers_screen_offer_admin_delete_offer');

function offers_screen_offer_admin() {
    if (!bp_is_offer_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_action_variables())
        return false;

    bp_core_redirect('edit-details');
    //bp_core_load_template(apply_filters('offers_template_offer_admin', 'offers/single/home'));
}

function offers_screen_offer_home() {

    if (!bp_is_single_item()) {
        return false;
    }

    bp_core_load_template(apply_filters('offers_template_offer_home', 'offers/single/home'));
}
?>



