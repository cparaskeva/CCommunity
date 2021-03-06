<?php
//Load CECommunity Header
global $firmasite_settings;
get_header('buddypress');

do_action("wp_enqueue_cecom_scripts");

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');
wp_enqueue_style('bootstrap-multiselect-style');
?>

<div id="primary" class="content-area-register <?php echo $firmasite_settings["layout_register_class"]; ?>">
    <!-- Registration progress bar -->
    <div class="progress progress-striped">
        <div id="progress_bar" style="width: 33%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar">
        </div>
    </div>


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
<div  hidden="true" id="current-step-errors" class="alert alert-danger col-md-3" style="color:red"></div>
<?php
//Load JavaScript Files
include(get_stylesheet_directory() . "/registration/register.js");
//Load CECommunity Footer
get_footer('buddypress');
?>


