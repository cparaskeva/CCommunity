<div name="page" id="register-page-step3">
    <h2>                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/step3.jpg" height="60" width="60">        
     <?php _e('Account Activation', 'firmasite'); ?></h2>
     <h3>Congratulations, you have successfuly registered to CECommunity Platform!</h3><br>
     
    <?php do_action('template_notices'); ?>
    <?php do_action('bp_before_account_details_fields'); ?>
    <p><?php _e('You just have to validate the email you received in your email inbox in order to access the Central Community platform.', 'firmasite'); ?></p>
    <br><p><strong><em><?php _e('Please note that if your organisation is already registered, you have also to be recognized as a member of your organisation by the administrator of your organisation.', 'firmasite'); ?></strong></em></p>
    <p align="right" id="backtoblog"><br><br><a href="<?php bloginfo('wpurl'); ?>" title="Are you lost?">&larr; Back to CECommunity</a></p>
</div>
