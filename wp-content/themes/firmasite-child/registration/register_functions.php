<?php

/*
 * Import LinkedIn API Call Functions 
 *
 * This class contains LinkedIn Application & User Tokens 
 * thus is excluded form github repository  
 */
include_once 'linkedin.php';

function custom_register_user() {

    /*
     *  Registration Step1
     */
    if (isset($_POST['register_step']) && $_POST['register_step'] == 'step1') {

        //Register Step1 Errors
        $errors = array();

        //Validate Username
        if (empty($_POST['signup_username']))
            $errors[] = 'You must provide a username|';
        else {
            $user_login = esc_attr($_POST['signup_username']);
            $sanitized_user_login = sanitize_user($user_login);
            if (empty($sanitized_user_login) || !validate_username($user_login))
                $errors[] = 'Invalid user name|';
            elseif (username_exists($sanitized_user_login))
                $errors[] = 'User name already exists|';
        }
        //Validate Email
        if (empty($_POST['signup_email']))
            $errors[] = 'Email address is required!|';
        else {
            $user_email = esc_attr($_POST['signup_email']);
            $user_email = apply_filters('user_registration_email', $user_email);
            if (!is_email($user_email))
                $errors[] = 'Invalid e-mail|';
            elseif (email_exists($user_email))
                $errors[] = 'This email is already registered|';
        }
        //Validate Password
        if (empty($_POST['signup_password']) || empty($_POST['signup_password_confirm']))
            $errors[] = 'Password fields canot be empty|';
        else {
            //Trim password fields
            $user_pass = trim($_POST['signup_password'], " \t\n\r\0\x0B\"\'");
            $password_confirm = trim($_POST['signup_password_confirm'], " \t\n\r\0\x0B\"\'");
            if (strlen($user_pass) < 6) {
                $errors[] = "Password must be at least 6 characters|";
            } else
            if (strcmp($user_pass, $password_confirm) != 0)
                $errors[] = "Confirmation password not match the password|";
        }
        //Validate Profile Name
        if (empty($_POST['profile_name']))
            $errors[] = 'Profile name is required|';
        else {
            $profile_name = $_POST['profile_name'];
            if (preg_match("/[^A-Za-z'-]/", $profile_name))
                $errors[] = 'Invalid first name|';
        }
        //Validate Profile Surname
        if (!empty($_POST['profile_surname'])) {
            if (preg_match("/[^A-Za-z'-]/", $_POST['profile_surname']))
                $errors[] = 'Invalid last name|';
        }


        if (!empty($errors)) {

            foreach ($errors as &$value) {
                echo($value);
            }
        } else {
            echo("step1_done");
            //Check if an organization already exist (based on the subdomain of the users' email)
            do_action("check_organization_exist", $user_email);
        }

        //End Of Step1
        exit();
    }


    /*
     *  Registration Step2
     *  AJAX CALL PARAMETERS  
     * (organization_id/name/description/specialties/website/size/type/
     * sector/country/collaboration/transaction)
     */

    if (isset($_POST['register_step']) && $_POST['register_step'] == 'step2') {

        //Register Step2 Errors
        $errors = array();

        //Map the POST paramters to an array
        $organization = array(
            'id' => ($_POST['cecom_organization_id']),
            'name' => rawurldecode($_POST['organization_name']),
            'description' => rawurldecode($_POST['organization_description']),
            'specialties' => rawurldecode($_POST['organization_specialties']),
            'website' => $_POST['organization_website'],
            'size' => $_POST['organization_size'],
            'type' => $_POST['organization_type'],
            'sectors' => explode(",",$_POST['organization_sectors']),
            'subsectors' =>  explode(",",$_POST['organization_subsectors']),
            'collaboration' => $_POST['organization_collaboration_y'],
            'transaction' => $_POST['organization_transaction_y'],
            'country' => $_POST['organization_country'],
            'email' => $_POST['signup_email'],
            'password' => $_POST['signup_password'],
            'username' => $_POST['signup_username'],
            'firstname' => $_POST['profile_name'],
            'surname' => $_POST['profile_surname'],
            'notlisted' => $_POST['organization_listed'],
        );

        //Check if user is selecting an already registered organisation
        if ($organization['notlisted'] == "false") {
            //User has not selected an organization from the list
            if ($organization['id'] == "undefined")
                $errors[] = "Please select your organization..";
        }
        else {

            //Validate Organization Name 
            if (empty($organization['name']))
                $errors[] = 'You must provide the name of the organization you belong|';
            //Validate Organization Website
            if (empty($organization['website']))
                $errors[] = 'You must provide the website of your organization|';
            //Validate Organization Size
            if (empty($organization['size']) || $organization['size'] == 'none' || !($organization['size'] >= 'A' && $organization['size'] <= 'I'))
                $errors[] = 'You must select the size of your organization|';
            //Validate Organization Type
            if (empty($organization['type']) || $organization['type'] == 'none' || !(preg_match("(C|D|E|G|N|O|P|S)", $organization['type'])))
                $errors[] = 'You must select the type of your organization|';
            //Validate Sector 
            $sectors = $organization['sectors'];
            $subsectors = $organization['subsectors'];
            if (empty($organization['sectors']) || $sectors[0] == 'null'   )
                $errors[] = 'You must select at least one sector for your organization|';
            //Validate Subsector  
            if (empty($organization['subsectors']) || $subsectors [0] == 'null')
                $errors[] = 'You must select  at least one  subsector for your organization|';



            //Formalize Collaboration Value to 0/1
            if ($organization['collaboration'] == 'on')
                $organization['collaboration'] = 1;
            else
                $organization['collaboration'] = 0;
            //Formalize Transaction Value to 0/1
            if ($organization['transaction'] == 'on')
                $organization['transaction'] = 1;
            else
                $organization['transaction'] = 0;
        }

        //Print the errors (if found)
        if (!empty($errors)) {

            foreach ($errors as &$value) {
                echo($value);
            }
        } else
            do_action_ref_array("register_organization", array(&$organization));

        //End Of Step2
        exit();
    }

    /* Subsector fields autload AJAX CALL based on Sector(s) ID */

    if (isset($_GET['operation']) && $_GET['operation'] == 'getSubsectors') {

        //Check if the sector id is valid
        if (!isset($_GET['sectors']) || $_GET['sectors'] == "") {
            exit();
        }
        //echo $_GET["callback"] . "(" . json_encode(CECOM_Organization::getOrganizationSubsector($_GET['sector_id'])) . ")";
        echo $_GET["callback"] . "(" . json_encode(CECOM_Organization::getOrganizationSubsectors($_GET['sectors'])) . ")";
        exit();
    }


    //Reject any other request
    echo "-1";
    exit();
}

add_action('wp_ajax_custom_register_user', 'custom_register_user');
add_action('wp_ajax_nopriv_custom_register_user', 'custom_register_user');

//LinkedIn AJAX Call Functions
function linkedin_api() {

    /**
     *   Linked Companies Serach
     *   @param string keyword User input
     *   @return json_string Companies id,name and website url of companies found 
     */
    if (isset($_GET['operation']) && $_GET['operation'] == 'autocomplete') {

        if (!empty($_GET['keyword']))
            echo $_GET["callback"] . "(" . LinkedIn::getLinkedInCompanies($_GET['keyword']) . ")";


        //End of Autocomplete
        exit();
    }

    /**
     *   Linked Company Profile Retrieval
     *   @param int companyID Company profile identificational number
     *   @return json_string Company profile fields(id,name,description,website,specialities,type,size)
     */
    if (isset($_POST['operation']) && $_POST['operation'] == 'getCompany') {

        if (!(isset($_POST['companyID'])))
            exit();
        else {
            $companyID = $_POST['companyID'];
            echo LinkedIn::getLinkedInCompanyInfo($companyID);
        }

        //End of Autocomplete
        exit();
    }
}

add_action('wp_ajax_linkedin', 'linkedin_api');
add_action('wp_ajax_nopriv_linkedin', 'linkedin_api');
?>