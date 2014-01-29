
<!--Prevent  a logged-in user to navigate into front-page -->
<?php
if (is_user_logged_in()) {
    global $bp;
    $redirection_url = $bp->loggedin_user->domain;
    wp_redirect($redirection_url);
    exit;
}
?>   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>CECommunity</title>
        <link href="<?php bloginfo('stylesheet_directory'); ?>/assets/css/login_page.css" rel="stylesheet" type="text/css" />
    </head>
<?php flush(); ?>

    <!-- Check if user is already logged-in, then redirect to profile page -->

    <body>
        <div id="wrapper">
            <div id="left_content">
                <span class="join"></span>
                <img class="logo" src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/logo.png" alt="CCommunity" width="321" height="83"  />
                <?php
                $users = BP_Core_User::get_users("active");
                $no_users = count($users["users"]);
                //$no_projects = Project::get_total_project_count();  	
                $no_networks = BP_Groups_Group::get_total_group_count();
                ?>
                <span class="login_message"><?php _e(sprintf("%d Registered Users", $no_users), 'icommunity'); ?></span>
                 <!-- <span class="login_message"><?php //_e(sprintf("%d projects", $no_projects), 'icommunity');   ?></span>-->
                <span class="login_message"><?php _e(sprintf("%d Organizations", $no_networks), 'icommunity'); ?></span>
                <span class="join" style="margin-top:20px">Join Us Today!</span>
            </div>

            <div id="right_content">
                <span class="title">Sign in to your account</span>
                <div id="left_form_content">
                    <div id="search_box">
                        <form method="post" id="searchform" action="<?php echo site_url('wp-login.php', 'login_post') ?>">
                            <fieldset class="search">

                                <input type="text" class="username" value="" name="log">
                                    <input type="password" class="password" value="" name="pwd">
                                        <input name="text" type="checkbox" class="checkbox" name="rememberme" value="forever" />
                                        <!-- <input type="hidden" name="redirect_to" value="members/"> -->
                                        <span class="remember"> Remember Me </span>
                                        </p>
                                        <button class="btn" title="Login">Login	</button>
                                        <span class="remember"> Login </span>
                                        </fieldset>
                                        </form>
                                        </div>
                                        </div>
                                        <div id="right_form_content">
                                            <a href="<?php echo site_url('wp-login.php?action=lostpassword', 'login') ?>">Forgot Password?</a>
<?php //do_action( 'bp_after_sidebar_login_form' )   ?>

                                        </div>
                                        <div id="info">To start connecting please log in first. You can also <span class="orange"><a href="<?php echo site_url('/register/') ?>">create an account</a></span>.</div>
                                        </div>





                                        <div id="footer">
                                            <div id="footer_logos">
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/footer_logo01.png"  />
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/footer_logo02.png"  />
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/footer_logo03.jpg" width="43" height="36" />
                                            </div>
                                            <div id="footer_text">
                                                <span class="copyright">Copyright © 2012 COLLECTIVE CENTRAL EUROPE programme | The Central Community project is implemented through the CENTRAL EUROPE programme co-financed by the ERDF.</span><br />
                                                <a href="<?php bloginfo('stylesheet_directory'); ?>/files/IPR/PRIVACY POLICY 1.1.docx">Privacy Policy</a> |  <a href="<?php bloginfo('stylesheet_directory'); ?>/files/IPR/TERMS OF USE 1.1.docx">Terms of Use</a> | <a href="<?php bloginfo('stylesheet_directory'); ?>/files/IPR/COPYRIGHT POLICY.docx">Copyright</a>
                                            </div>
                                        </div>
                                        </div>
<?php do_action('wp_footer') ?>
                                        </body>
                                        </html>
