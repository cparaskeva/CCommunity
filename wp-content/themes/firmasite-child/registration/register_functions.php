<?php

/*
 * Import LinkedIn API Call Functions 
 *
 * This class contains LinkedIn Application & User Tokens 
 * thus is excluded form github repository  
 */
require_once 'linkedin.php';

function custom_register_user() {

    /*
     *  Registration Step1
     */
    if (isset($_POST['register_step']) && $_POST['register_step'] == 'step1') {

        //Register Step1
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
            /*
              $user_id = wp_create_user($sanitized_user_login, $user_pass, $user_email);
              if (!$user_id):
              $errors[] = 'Registration failed';
              else:
              echo("User: " . $user_id . " registered with password:\"" . $user_pass . "\"|");

              //Force user to active the account
              //TODO: Create a function in order to change the user_status
              global $wpdb;
              $wpdb->update($wpdb->users, array(sanitize_key("user_status") => 2), array('ID' => $user_id));

              //Buddypress xprofile data
              xprofile_set_field_data('Name', $user_id, $profile_name);
              xprofile_set_field_data('Surname', $user_id,  $_POST['profile_surname']);

              if ($user_id && !is_wp_error($user_id)) {
              $key = sha1($user_id . time());
              $activation_link = bp_get_activation_page() . "?key=$key";
              add_user_meta($user_id, 'activation_key', $key, true);
              wp_mail($user_email, 'CECommunity ACTIVATION', 'You have succesfuly register to CECommunity platform.\n Activate your account using this link: ' . $activation_link);
              }
              endif;
             * 
             */
        }

        //End Of Step1
        exit();
    }


    if (isset($_POST['register_step']) && $_POST['register_step'] == 'step2') {
        
    }
}

add_action('wp_ajax_custom_register_user', 'custom_register_user');
add_action('wp_ajax_nopriv_custom_register_user', 'custom_register_user');

//LinkedIn AJAX Call Functions
function linkedin_api() {

    /*
     *   Linked Companies Serach
     */
    if (isset($_GET['operation']) && $_GET['operation'] == 'autocomplete') {
 $tmp = "{\"values\": [ {\"id\": 1362084,\"name\": \"UBI Assicurazioni\" }, {\"id\": 3017386,\"name\": \"Ubi Interactive\"}]}";

        if (!empty($_GET['keyword']))
            echo $_GET["callback"] . "(" . LinkedIn::getLinkedInCompanies($_GET['keyword']) . ")";
        
            
            
          //echo $_GET["callback"] . "(" . ($tmp) . ")";

        //End of Autocomplete
        exit();
    }
}

add_action('wp_ajax_linkedin', 'linkedin_api');
add_action('wp_ajax_nopriv_linkedin', 'linkedin_api');
?>