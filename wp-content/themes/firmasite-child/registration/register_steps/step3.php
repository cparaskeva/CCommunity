<div name="page" id="register-page-step3" hidden="true" >
    <h2>                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/3.png" height="60" width="60">        
        <?php _e('Account Activation', 'firmasite'); ?></h2>
    <h3>Congratulations, you have successfully registered to LifeScienceRoom!</h3><br>
    <?php do_action('template_notices'); ?>
    <?php do_action('bp_before_account_details_fields'); ?>
    <p><?php _e('Please check your email and click the validation link in the email we just sent you to access LifeScienceRoom.', 'firmasite'); ?></p>
    <br><p><strong><em><?php _e('Please note that if your organisation is already registered, you also have to be recognised as a member of it by the administrator of your organisation. If instead you are the first member of a newly registered organisation, you will be the administrator of the organisation in the LifeScienceRoom platform. It means you will have the ability to perform some operations in the platform on behalf of your organisation.', 'firmasite'); ?></strong></em></p>
    <p align="right" id="backtoblog"><br><br><a href="<?php bloginfo('wpurl'); ?>" title="Are you lost?">&larr; Back to Home</a></p>
</div>
