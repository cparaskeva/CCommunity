<?php

class CECOM_Organization {
    /* Singleton pattern  */

    private static $instance;

    public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new CECOM_Organization;
            self::$instance->setup_actions();
        }
        return self::$instance;
    }

    /**
     * Define all the necesary actions of an Organization
     * 
     * 
     */
    private function setup_actions() {
        add_action("register_organization", "registerOrganization");
    }

    /* Static Functions */

    //Fetch the available types for an organization
    public static function getOrganizationType() {
        global $wpdb;
        $organization_type = $wpdb->get_results("SELECT * FROM ext_organization_type");
        if (!is_array($organization_type))
            return nil;
        return $organization_type;
    }

    //Fetch the prossible number of employees  for an organization
    public static function getOrganizationSize() {
        global $wpdb;
        $organization_size = $wpdb->get_results("SELECT * FROM ext_organization_size");
        if (!is_array($organization_size))
            return nil;
        return $organization_size;
    }

    //Fetch the prossible number of employees  for an organization
    public static function getOrganizationSector() {
        global $wpdb;
        $organization_sector = $wpdb->get_results("SELECT * FROM ext_organization_sector");
        if (!is_array($organization_sector))
            return nil;
        return $organization_sector;
    }

    //Fetch registered organizations
    public static function getRegisteredOrganizations() {
        global $wpdb;
        $organizations = $wpdb->get_results("SELECT gid,name,website FROM ext_organization");
        if (!is_array($organizations))
            return nil;
        return $organizations;
    }

    //Returns the group ID which is associated with the organization
    public static function getGroupID($organization_id) {
        global $wpdb;
        $wpdb->get_var('select gid from ext_organization where id=' . $organization_id);
    }

}

//Initialize Organization object
$this->organization = CECOM_Organization::instance();

//Implementation of Organization Actions

function registerOrganization($organization) {


    /* Create User account */

    $sanitized_user_login = $organization['username'];

    //Someone is doing nasty things... Abort Immediately
    if (username_exists($sanitized_user_login)) {
        echo "-1";
        return;
    }

    global $wpdb;

    $user_id = wp_create_user($sanitized_user_login, $organization['password'], $organization['email']);
    if (!$user_id):
        //Something gone wrong... Abort Registration
        echo "-1";
        return;
    else:

        //Force user to active the account
        //TODO: Create a function in order to change the user_status
        $wpdb->update($wpdb->users, array(sanitize_key("user_status") => 2), array('ID' => $user_id));

        //Buddypress xprofile data
        xprofile_set_field_data('Name', $user_id, $organization['firstname']);
        xprofile_set_field_data('Surname', $user_id, $organization['surname']);


        //Send confirmation email to the user
        if ($user_id && !is_wp_error($user_id)) {
            $key = sha1($user_id . time());
            $activation_link = bp_get_activation_page() . "?key=$key";
            add_user_meta($user_id, 'activation_key', $key, true);
            wp_mail($organization['email'], 'CECommunity ACTIVATION', 'You have succesfuly registered to CECommunity platform. Activate your account using this link: ' . $activation_link);
        }

        $group_id = $organization['id'];

        if ($group_id == "undefined") {

            /* Create buddypress group */

            $group = array(
                'creator_id' => $user_id,
                'name' => $organization['name'],
                'slug' => 'organization' . $user_id,
                'description' => $organization['description'],
                'status' => 'private',
                'enable_forum' => 0,
                'date_created' => bp_core_current_time()
            );

            //Create a new group and get the group id
            $group_id = groups_create_group($group);

            //Check if group creation is success
            if ($group_id < 1) {
                echo "-1";
                return;
            }

            /* Register organization */

            //Oganization does not exist

            $wpdb->show_errors();
            $status = $wpdb->insert('ext_organization', array(
                'gid' => $group_id,
                'name' => $organization['name'],
                'size_id' => $organization['size'],
                'type_id' => $organization['type'],
                'country_id' => $organization['country'],
                'sector_id' => $organization['sector'],
                'subsector_id' => '1',
                'collaboration' => $organization['collaboration'],
                'transaction' => $organization['transaction'],
                'website' => $organization['website'],
                'description' => $organization['description'],
                'specialties' => $organization['specialties']), array('%d', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s', '%s', '%s')
            );


            //Something gone really bad... (Possible Action: trying to overwrite an existing organization)
            if ($status < 1)
                echo -1;
            else
            //Registration has been successful...
                echo 1;
        }
        //Oganization already exist 
        else {
            //groups_join_group($group_id, $user_id);
            groups_send_membership_request($user_id,$group_id);
            echo 1;
        }

    endif;
}

?>
