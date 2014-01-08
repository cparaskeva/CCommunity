<div class="page" id="register-page-step2">

    <form action="" name="register_organization_form" id="register_organization_form" class="standard-form form-horizontal" method="post" enctype="multipart/form-data">


            <h2>                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/step2.jpg" height="60" width="60">        
                <?php _e('Register an Organization', 'firmasite'); ?></h2>

            <?php do_action('template_notices'); ?>

            <p><?php _e('In the case tha the organization tha you belong is not listed, you can register it manually or get your organization info using your linked in account.', 'firmasite'); ?></p>

            <?php do_action('bp_before_account_details_fields'); ?>

            <div class="register-section" id="basic-details-section">

                <?php /*                 * *** Basic Account Details ***** */ ?>

                <h4 class="page-header"><?php _e('Organization Details', 'firmasite'); ?></h4>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="signup_username"><?php _e('Username', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                    <div class="col-xs-12 col-md-9">
                        <?php do_action('bp_signup_username_errors'); ?>
                        <input type="text" class="form-control" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" <?php if (bp_get_the_profile_field_is_required()) : ?>aria-required="true"<?php endif; ?>/>
                    </div>
                </div>

 </div>

    </form>

</div>
