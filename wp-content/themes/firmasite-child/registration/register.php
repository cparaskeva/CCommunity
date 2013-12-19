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
                    <form action="" name="signup_form" id="signup_form" class="standard-form form-horizontal" method="post" enctype="multipart/form-data">
                        <?php
                        //echo get_stylesheet_directory_uri();
                        //echo (get_template_directory());
//Main registration code
                        //Check if registration is allowed!
                        if (!get_option('users_can_register')) {
                            _e('User registration is currently not allowed.', 'firmasite');
                        } else {
                            // _e('User registration is currently  allowed.', 'firmasite');

                            _e("<h2>  <img src=\"" . get_stylesheet_directory_uri() . "/assets/img/step1.jpg\" height=\"60\" width=\"60\">        
                           Create an Account</h2>");
                            _e("<div class=\"register-section\" id=\"basic-details-section\">");
                            ?>    
                            <p><?php _e('The registering process will guide you among the nessecary steps in order to create an account and have access to the platform. <strong>First step complete all the required fields to create an account. </Strong>', 'firmasite'); ?></p>


                            <div class="register-section" id="basic-details-section">

                                <?php /*                                 * *** Basic Account Details ***** */ ?>
                                <?php do_action('template_notices'); ?>
                                <h4 class="page-header"><?php _e('Account Details', 'firmasite'); ?></h4>

                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-md-3" for="signup_username"><?php _e('Username', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                                    <div class="col-xs-12 col-md-9">
                                        <?php do_action('bp_signup_username_errors'); ?>
                                        <input type="text" class="form-control" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-md-3" for="signup_email"><?php _e('Email Address', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                                    <div class="col-xs-12 col-md-9">
                                        <?php do_action('bp_signup_email_errors'); ?>
                                        <input type="text" class="form-control" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-md-3" for="signup_password"><?php _e('Choose a Password', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                                    <div class="col-xs-12 col-md-9">
                                        <?php do_action('bp_signup_password_errors'); ?>
                                        <input type="password" class="form-control" name="signup_password" id="signup_password" value="" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-md-3" for="signup_password_confirm"><?php _e('Confirm Password', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                                    <div class="col-xs-12 col-md-9">
                                        <?php do_action('bp_signup_password_confirm_errors'); ?>
                                        <input type="password" class="form-control" name="signup_password_confirm" id="signup_password_confirm" value="" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                                    </div>
                                </div>

                            </div><!-- #basic-details-section -->
                        </form>


                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
</div>


<?php
//Load CECommunity Footer
get_footer('buddypress');
//Load JavaScript Files
//include("/home/demonas/IDEs_Projects/NetBeansProjects/CCommunity/wp-content/themes/firmasite-child/registration/register.js" );
include(get_stylesheet_directory()."/registration/register.js");
?>


