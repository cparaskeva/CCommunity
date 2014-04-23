<?php

class CECOM_Organization {
    /* Singleton pattern  */

    private static $instance;
    public $details = array(
        'id' => '',
        'country' => '',
        'type' => '',
        'size' => '',
        'sector' => '',
        'collaboration' => '',
        'transaction' => '',
        'sepcialties' => '',
        'website' => '',
        'country' => '',
        'size_min' => '',
        'size_max' => '',
        'sectors' => '',
        'subsectors' => ''
    );

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
        add_action("check_organization_exist", "checkOrganization");
    }

    /* Static Functions */

    //Get all the organization details


    public function setOrganizationDetails($group_id) {
        global $wpdb;

        //Get organization details
        $org_details = $wpdb->get_row("select org.id,website,specialties,min size_min, max size_max, collaboration,transaction, type.description type_desc, country_id "
                . " from ext_organization org, ext_organization_size size, ext_organization_type type " .
                "where org.gid =" . $group_id . " and type_id=type.id and size_id = size.id ");
        self::$instance->details['specialties'] = $org_details->specialties;
        self::$instance->details['website'] = $org_details->website;
        self::$instance->details['collaboration'] = $org_details->collaboration;
        self::$instance->details['transaction'] = $org_details->transaction;
        self::$instance->details['type'] = $org_details->type_desc;
        self::$instance->details['country'] = $org_details->country_id;
        self::$instance->details['size_min'] = $org_details->size_min;
        self::$instance->details['size_max'] = $org_details->size_max;
        self::$instance->details['id'] = $org_details->id;

        /* Get organization metadata */

        //Get organization sectors
        self::$instance->details['sectors'] = $wpdb->get_results("SELECT s.id,s.color,s.description from ext_organization_meta m,ext_organization_sector s where m.mkey='sector' and m.mvalue = s.id and oid=$org_details->id", ARRAY_A);
        //Get organization subsectors
        self::$instance->details['subsectors'] = $wpdb->get_results("SELECT s.id,s.description from ext_organization_meta m,ext_organization_subsector s where m.mkey='subsector' and m.mvalue = s.id and oid=$org_details->id", ARRAY_A);
    }

    //Checks if a use has a valid membership to an organization
    public static function check_user_organization_membership($user) {
        $username = $user->data->user_login;
        $user_id = $user->data->ID;
        if (!$user_id)
            return;
        global $wpdb;
        $membership_status = $wpdb->get_var('SELECT is_confirmed from wp_bp_groups_members where user_id=' . $user_id);
        //echo "Membership status: ".$membership_status;die();
        if (!$membership_status) {
            $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: You organisation admin has not yet accepted your membership!'));
            do_action('wp_login_failed', $username);
        }
        return $user;
    }

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

    //Fetch the all the subectors of a specific sector
    public static function getOrganizationSubsector($sectorID) {
        global $wpdb;
        $organization_subsector = $wpdb->get_results("SELECT * FROM ext_organization_subsector where sid=" . $sectorID . " order by description asc");
        if (!is_array($organization_subsector))
            return nil;
        return $organization_subsector;
    }

    //Fetch all the subsectors based on specific sectors
    public static function getOrganizationSubsectors($subsectors) {
        global $wpdb;
        $organization_subsectors = $wpdb->get_results("SELECT * FROM ext_organization_subsector where sid in (" . implode(",", $subsectors) . ")order by  sid, description asc");
        if (!is_array($organization_subsectors))
            return nil;
        return $organization_subsectors;
    }

    //Fetch registered organizations
    public static function getRegisteredOrganizations() {
        global $wpdb;
        $organizations = $wpdb->get_results("SELECT gid,name,website FROM ext_organization");
        if (!is_array($organizations))
            return nil;
        return $organizations;
    }

    //Fetch All countries
    public static function getAllCountries() {
        global $wpdb;
        $organizations = $wpdb->get_results("SELECT * FROM ext_organization_country order by name asc");
        if (!is_array($organizations))
            return nil;
        return $organizations;
    }

    //Returns the group ID which is associated with the organization
    public static function getUserGroupID($organization_id = 0) {
        global $wpdb;
        if ($organization_id > 0)
            return $wpdb->get_var('select gid from ext_organization where id=' . $organization_id);
        else {
            global $bp;
            //Get the groupd id  of the group that the user is member
            return $wpdb->get_var('select group_id from wp_bp_groups_members where user_id=' . $bp->loggedin_user->id);
            /* Uncomment for retrieving groupID without using a query - Debug purposes only!
              $group = groups_get_user_groups($bp->loggedin_user->id);
              $gid = $group['groups'][0];
              echo  "First groupID ".$gid .  " of User ID: ".$bp->loggedin_user->id; */
        }
    }

    public static function getUserOrganizationID($groupID) {
        global $wpdb;
        if ($groupID)
            return $wpdb->get_var('select id from ext_organization where gid=' . $groupID);
    }

    public static function getOrganizationOfferDetails($groupID) {
        global $wpdb;
        if ($groupID)
            return $wpdb->get_row('select name,slug,id from wp_bp_groups where id=' . $groupID, ARRAY_A);
    }

    //Update Organization Profile 
    public static function edit_organization_details($org_id, $group_id, $desc, $name, $specialties, $website, $country, $type, $size, $sectors, $subsectors, $collaboration, $transaction) {
        global $wpdb;
        $wpdb->query($wpdb->prepare("UPDATE ext_organization  SET "
                        . "description   = %s ,"
                        . "name          = %s ,"
                        . "size_id       = %s ,"
                        . "type_id       = %s ,"
                        . "country_id    = %s ,"
                        . "website       = %s ,"
                        . "specialties   = %s ,"
                        . "collaboration = %d ,"
                        . "transaction   = %d "
                        . "WHERE gid     = %d ", $desc, $name, $size, $type, $country, $website, $specialties, $collaboration, $transaction, $group_id));

        //Clear the old metadata
        $wpdb->get_results("DELETE  FROM `ext_organization_meta` where oid= $org_id");
        //Store the updated metadata
        $metadata = array("sector" => explode(",", $sectors), "subsector" => explode(",", $subsectors));
        CECOM_Organization::saveMetadata($org_id, $metadata);
    }

    //Save meta data to ext_organization_meta table
    public static function saveMetadata($orgID, $metadata) {
        global $wpdb;
        //Check if a valid orgID is given
        if ($orgID) {
            $query = "INSERT INTO ext_organization_meta (oid,mkey,mvalue) VALUES ";
            //$metadata ($key => Array) Two dimensions array
            foreach ($metadata as $mkey => $mvalue) {
                foreach ($mvalue as $key => $value) {
                    $query .= "($orgID,'$mkey','$value') ,";
                }
            }
            //Remove last ","
            $query = substr($query, 0, -1);

            //Execute Query
            $wpdb->get_results($query);
        }
    }

    //Build the meta query based on the arguments given in the organisation search form 
    public static function build_search_meta_query($search_extras) {

        $search_extras_query = '';
        //Convert search_extras values to an array of arguments
        if (!empty($search_extras)) {
            $search_extras_args = array();
            $asArr = explode('|', $search_extras);

            foreach ($asArr as $val) {
                $tmp = explode(';', $val);
                $search_extras_args[$tmp[0]] = $tmp[1];
            }
            //If calculation is success continue
            if (!empty($search_extras_args)) {

                //Take into account organization size field
                $search_extras_query.= ($search_extras_args['organization-size'] != 'none' ? "AND size_id='{$search_extras_args['organization-size']}' " : "");
                //Take into account organization type field
                $search_extras_query.= ($search_extras_args['organization-type'] != 'none' ? "AND type_id='{$search_extras_args['organization-type']}' " : "");
                //Take into account organization country field
                $search_extras_query.= ($search_extras_args['organization-country'] != '' ? "AND country_id='{$search_extras_args['organization-country']}' " : "");
                //Take into account collaboration field
                $search_extras_query.= ($search_extras_args['organization-collaboration'] != '' ? "AND collaboration='{$search_extras_args['organization-collaboration']}' " : "");
                //Take into account transaction field
                $search_extras_query.= ($search_extras_args['organization-transaction'] != '' ? "AND transaction='{$search_extras_args['organization-transaction']}' " : "");

                //Handle sectors and subsectors fields
                $search_extras_subquery = '';
                if (!empty($search_extras_args['organization-sectors'])) {
                    $sectors_query = "(mkey='sector' and mvalue in ({$search_extras_args['organization-sectors']})";
                    $sectors_query = (!empty($search_extras_args['organization-subsectors']) ? " (mkey='subsector' and mvalue in ({$search_extras_args['organization-subsectors']})" : $sectors_query);
                    $search_extras_subquery = (!empty($search_extras_query) ? " AND " : "") . " org.id in (select oid from ext_organization_meta where {$sectors_query}))";
                }

                /*
                 * Handle Serach Organization based on the offer types 
                 * 
                 * Supported:
                 * 
                 * 1.) Search an organisation to develop a product or a service
                 * 2.) Search an organisation offering funded projects
                 * 
                 */
                $search_offers_subquery = '';
                if (bp_offers_current_category() != "none" && bp_offers_current_category() != 3) {

                    //Take into account collaboration description field
                    $search_offers_subquery.= (!empty($search_extras_args['collaboration-description']) ? "AND description LIKE '%%{$search_extras_args['collaboration-description']}%%' " : "");
                    //Take into collaboration type field
                    $search_offers_subquery.= ($search_extras_args['collaboration-type'] != 'none' ? "AND collaboration_id='{$search_extras_args['collaboration-type']}' " : "");
                    //Offer Type[1] : develop a product or a service - extra field  -> collaboration-partner-sought
                    $search_offers_subquery.= ($search_extras_args['offer-type'] == "1" && $search_extras_args['collaboration-partner-sought'] != "none" ? "AND partner_type_id =' {$search_extras_args['collaboration-partner-sought']}' " : "" );
                    //Offer Type[2] : offering funded projects - extra field -> collaboration-programs
                    $search_offers_subquery.= ($search_extras_args['offer-type'] == "2" && $search_extras_args['collaboration-programs'] != "none" ? "AND program_id =' {$search_extras_args['collaboration-programs']}' " : "" );


                    //if (!empty($search_offers_subquery))
                    $search_offers_subquery = " AND g.id in (SELECT gid from ext_offer WHERE type_id='{$search_extras_args['offer-type']}' " . $search_offers_subquery . " ) ";

                    //echo "Supported category ".bp_offers_current_category(); 
                    //echo "Offers Subquery : " .$search_offers_subquery;
                    // and gid in (select gid from ext_offer where type_id =1)
                }



                //If query not empty build final search meta query
                if (!empty($search_extras_query) || !empty($search_extras_subquery))
                    $search_extras_query = " AND g.id in ( SELECT gid from ext_organization as org WHERE " . substr($search_extras_query, 4) . $search_extras_subquery . ")";
            }
        }
        return $search_extras_query . $search_offers_subquery;
    }

    /* The following static functions are used only by the Alerts Component */

    //Return the most recent registered organizations since the previous Cron check
    public static function fetch_recent_registered_organizations($since_time) {
        global $wpdb;
        $prefix_values = "org.id,gid,org.name,org.description,size_id size,type_id type,country_id country,collaboration,transaction,slug";
        return $wpdb->get_results("SELECT $prefix_values FROM wp_bp_groups groups,ext_organization org WHERE groups.id=org.gid AND groups.date_created >= '$since_time' ", ARRAY_A);
    }

    //Return the most recent offers of an organizations since the previous Cron check
    public static function fetch_recent_organization_offers($since_time, $offer_type) {
        global $wpdb;
        $prefix_values = "org.id,org.gid,org.name,org.description,size_id size,org.type_id type,org.country_id country,collaboration,transaction,slug,offer.id oid,offer.type_id offtype_id,offer.collaboration_id,offer.description offdescription," . ($offer_type == 1 ? "partner_type_id" : "program_id");
        //echo "Query: "."SELECT $prefix_values FROM ext_organization org,ext_offer offer,wp_bp_groups groups WHERE groups.id=org.gid AND org.gid=offer.gid AND offer.type_id=$offer_type AND offer.date >= '$since_time'";
        return $wpdb->get_results("SELECT $prefix_values FROM ext_organization org,ext_offer offer,wp_bp_groups groups WHERE groups.id=org.gid AND org.gid=offer.gid AND offer.type_id=$offer_type AND offer.date >= '$since_time' ", ARRAY_A);
    }

    public static function check_for_interested_organizations($new_organizations, $interested_organizations) {
        foreach ($new_organizations as $organization)
            foreach ($interested_organizations as $interested_organization)
                self::check_for_matching_criteria($organization, $interested_organization);
    }

    public static function check_for_matching_criteria($organization, $interested_organization) {
        /* print_r($organization);
          echo "<br><br>";
          print_r($interested_organization);
          echo "<br><br>"; */

        if ($organization['gid'] == $interested_organization['gid'])
            return;

        //If is an offer serach normalize the fields
        if ($organization['offtype_id']) {
            $organization['collaboration'] = "none";
            $organization['transaction'] = "none";
            $interested_organization['action_query'] = str_replace("collaboration-type", "collaboration_id", $interested_organization['action_query']);
            $interested_organization['action_query'] = str_replace("collaboration-partner-sought", "partner_type_id", $interested_organization['action_query']);
            $interested_organization['action_query'] = str_replace("offer-type", "offtype_id", $interested_organization['action_query']);
            $interested_organization['action_query'] = str_replace("collaboration-programs", "program_id", $interested_organization['action_query']);
        }

        $organization_criteria = array();
        $tmp_array = explode('|', $interested_organization['action_query']);

        foreach ($tmp_array as $val) {
            $tmp = explode(';', $val);
            if ($tmp[1] != "" && $tmp[1] != "none" && $tmp[1] != "on") {
                if ($tmp[0] == "text")
                    $text = $tmp[1];
                else if ($tmp[0] == "collaboration-description")
                    $offer_text = $tmp[1];
                else {
                    if ($tmp[0] == "organization-sectors") {
                        $sectors_criteria = $tmp[1];
                        $sectors_tmp = explode(',', $tmp[1]);
                        foreach ($sectors_tmp as $value) {
                            $sectors["sector" . $value] = $value;
                        }
                        unset($sectors_tmp);
                    } else if ($tmp[0] == "organization-subsectors") {
                        $subsectors_criteria = $tmp[1];
                        $subsectors_tmp = explode(',', $tmp[1]);
                        foreach ($subsectors_tmp as $value) {
                            $subsectors["subsector" . $value] = $value;
                        }
                        unset($subsectors_tmp);
                    } else if (!strpos("@" . $tmp[0], "organization_"))
                        $organization_criteria[( strpos("@" . $tmp[0], "organization-") > 0 ? substr($tmp[0], 13) : $tmp[0])] = $tmp[1];
                }
            }
        }

        //print_r($organization_criteria);
        $criteria_num = count($organization_criteria);
        $extra_criteria_num = count($sectors_criteria) + count($subsectors_criteria);
        $text_criteria = (empty($text) ? 0 : 1) + (empty($offer_text) ? 0 : 1);
        $total_criteria_num = $criteria_num + $extra_criteria_num + $text_criteria;
        $sectors;
        $subsectors;
        $matched_extra_criteria = 0;

        //Calculate sectors and subsectors matches
        if ($extra_criteria_num) {

            global $wpdb;
            $complexArray = ($wpdb->get_results("SELECT mvalue sector FROM `ext_organization_meta` WHERE oid={$organization['id']} and mkey='sector'", ARRAY_A));
            $singleArray = array();

            if (!empty($sectors)) {
                foreach ($complexArray as $key => $value) {
                    $singleArray["sector" . $value['sector']] = $value['sector'];
                }
                if (count($sectors) == count(array_intersect_assoc($sectors, $singleArray)))
                    $matched_extra_criteria++;
            }

            if (!empty($subsectors)) {
                $complexArray = ($wpdb->get_results("SELECT mvalue subsector FROM `ext_organization_meta` WHERE oid={$organization['id']} and mkey='subsector'", ARRAY_A));
                $singleArray = array();
                foreach ($complexArray as $key => $value) {
                    $singleArray["subsector" . $value['subsector']] = $value['subsector'];
                }
                if (count($subsectors) == count(array_intersect_assoc($subsectors, $singleArray)))
                    $matched_extra_criteria++;
            }
        }

        $matched_criteria = count(array_intersect_assoc($organization, $organization_criteria));

        //Check if description or  name of the organization match the text of interested organization
        $matched_text_criteria = (strpos("@" . $organization['name'], $text) > 0 || strpos("@" . $organization['description'], $text) > 0 ? 1 : 0 ) + (strpos("@" . $organization['offdescription'], $offer_text) > 0 ? 1 : 0 );
        $matched_all_criteria = ($total_criteria_num == ($matched_criteria + $matched_extra_criteria + $matched_text_criteria));

        if ($_GET['debug']) {
            echo "Criteria: $criteria_num  Extra criteria: $extra_criteria_num Text criteria: $text_criteria <br>";
            echo "Total criteria: $total_criteria_num Matched criteria: $matched_criteria Matched extra criteria: $matched_extra_criteria Matched text: $matched_text_criteria <br><br>";
        }

        //Organization matches all the criteria of the alert
        if ($matched_all_criteria) {
            if ($_GET['debug'])
                echo "<b>Found exact criteria matched! OrgID:{$organization['id']}  OfferID: {$organization['oid']} AlertID:{$interested_organization['id']}  </b>";
            BP_Alert_Factory::notify_alert_user($interested_organization['action_id'], $interested_organization['uid'], $interested_organization['id'], array('slug' => $organization['slug'], 'offer_id' => ($organization['oid'] ? $organization['oid'] : 0)));
        }
        if ($_GET['debug'])
            echo "<hr>";
    }

    /* End of alerts component functions */
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
        xprofile_set_field_data('Name', $user_id, trim($organization['firstname'], " \t\n\r\0\x0B\"\'"));
        xprofile_set_field_data('Surname', $user_id, trim($organization['surname'], " \t\n\r\0\x0B\"\'"));


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
            $status = $wpdb->insert('ext_organization', array(
                'gid' => $group_id,
                'name' => $organization['name'],
                'size_id' => $organization['size'],
                'type_id' => $organization['type'],
                'country_id' => $organization['country'],
                'collaboration' => $organization['collaboration'],
                'transaction' => $organization['transaction'],
                'website' => $organization['website'],
                'description' => $organization['description'],
                'specialties' => $organization['specialties']), array('%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s')
            );


            //Something gone really bad... (Possible Action: trying to overwrite an existing organization)
            if ($status < 1)
                echo -1;
            else {
                /* Final step, save the sectors and subcectors of the organization */
                $orgID = CECOM_Organization::getUserOrganizationID($group_id);
                $metadata = array("sector" => $organization['sectors'], "subsector" => $organization['subsectors']);
                CECOM_Organization::saveMetadata($orgID, $metadata);
                //Registration has been successful...
                echo 1;
            }
        }
        //Oganization already exist 
        else {
            //groups_join_group($group_id, $user_id);
            groups_send_membership_request($user_id, $group_id);
            echo 1;
        }

    endif;
}

//Check for a matached subdomain based on the users' registering email
function checkOrganization($email) {
    global $wpdb;
    $domain = explode('@', $email);
    //Check if subdomain is set
    if (isset($domain[1])) {
        $result = $wpdb->get_row("select gid,website,name from ext_organization where website like '%" . $domain[1] . "' limit 1");
        echo "|" . $result->gid . "|" . $result->name . "|" . $result->website;
    }
}

//Get the n latest created organisations
function getLatestOrganisations($nbOrg = 5) {
    global $wpdb;

    $orgs = $wpdb->get_results("SELECT *  FROM ext_organization o INNER JOIN wp_bp_groups g ON o.gid=g.id ORDER BY o.id DESC LIMIT $nbOrg");
    //syslog(LOG_INFO, var_export($orgs, true));
    return $orgs;
}

?>
