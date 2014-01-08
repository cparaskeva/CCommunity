<?php
//Load CECommunity Header
global $firmasite_settings;
get_header('buddypress');
?>

<div id="primary" class="content-area-register <?php echo $firmasite_settings["layout_register_class"]; ?>">
    <div class="padder">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="page" id="register-page"> 

                    <?php
                    //Main registration code
                    //Check if registration is allowed!
                    if (!get_option('users_can_register')) {
                        _e('User registration is currently not allowed.', 'firmasite');
                    } else {

                        //Registration Step1 
                        include(get_stylesheet_directory() . "/registration/register_steps/step1.php");

                        //Registration Step2 
                        include(get_stylesheet_directory() . "/registration/register_steps/step2.php");

                        //Registration Step3 
                        include(get_stylesheet_directory() . "/registration/register_steps/step3.php");
                    }
                    ?>



                </div>

            </div>
        </div>

    </div>
</div>
</div>

<div id="current-step-errors" style="color:red"></div>

<?php
//Load JavaScript Files
include(get_stylesheet_directory() . "/registration/register_assist.js");
//Load CECommunity Footer
get_footer('buddypress');
?>


