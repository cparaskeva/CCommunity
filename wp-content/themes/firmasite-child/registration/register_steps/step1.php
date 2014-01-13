<div class="page" id="register-page-step1">
    <form name="signup_form" id="register_step1" class="standard-form form-horizontal" method="post" enctype="multipart/form-data" action="">
        <?php
        _e("<h2>  <img src=\"" . get_stylesheet_directory_uri() . "/assets/img/step1.jpg\" height=\"60\" width=\"60\">        
                           Create an Account</h2>");
        _e("<div class=\"register-section\" id=\"basic-details-section\">");
        ?>    
        <p><?php _e('The registering process will guide you among the nessecary steps in order to create an account and have access to the platform. <strong>First step complete all the required fields to create an account. </Strong>', 'firmasite'); ?></p>


        <div class="register-section" id="basic-details-section">
            <input type="hidden" class="form-control" name="register_step" id="register_step" value="step1"/>
            <?php /*             * *** Basic Account Details ***** */ ?>
            <?php do_action('template_notices'); ?>
            <h4 class="page-header"><?php _e('Account Details', 'firmasite'); ?></h4>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="signup_username"><?php _e('Username', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                <div class="col-xs-12 col-md-9">
                    <input type="text" class="form-control" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="signup_email"><?php _e('Email Address', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                <div class="col-xs-12 col-md-9">
                    <input type="text" class="form-control" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="signup_password"><?php _e('Choose a Password', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                <div class="col-xs-12 col-md-9">
                    <input type="password" class="form-control" name="signup_password" id="signup_password" value="" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                    <p class="field-visibility-settings-toggle text-muted" id="password-info">
                        <?php printf(__('Password must be at least 6 characters')); ?>
                    </p>    
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="signup_password_confirm"><?php _e('Confirm Password', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                <div class="col-xs-12 col-md-9">
                    <input type="password" class="form-control" name="signup_password_confirm" id="signup_password_confirm" value="" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                </div>
            </div>


        </div><!-- #basic-details-section -->



        <!-- **** Extra Profile Details ****** -->

        <div class="form-group">
            <label class="control-label col-xs-12 col-md-3" for="profile_name"><?php _e('Name', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
            <div class="col-xs-12 col-md-9">
                <input type="text" class="form-control" name="profile_name" id="profile_name" value="<?php bp_signup_username_value(); ?>" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                <p class="field-visibility-settings-toggle text-muted" id="xprofile-name">
                    <?php printf(__('Display on profile page')); ?>
                </p>    
            </div>
        </div>



        <div class="form-group">
            <label class="control-label col-xs-12 col-md-3" for="profile_surname"><?php _e('Surname', 'firmasite'); ?></label>
            <div class="col-xs-12 col-md-9">
                <input type="text" class="form-control" name="profile_surname" id="profile_surname" value="<?php bp_signup_username_value();?>" aria-required="false"/>
                <p class="field-visibility-settings-toggle text-muted" id="xprofile-surname">
                    <?php printf(__('Display on profile page')); ?>
                </p>   
            </div>
        </div>

        <div align="right" class="submit" >
            <input type="submit" class="btn  btn-primary" name="signup_submit" id="signup_submit" value="<?php _e('Submit', 'firmasite'); ?>" />
        </div>

    </form>
</div>