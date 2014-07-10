
<!--Prevent  a logged-in user to navigate into front-page -->
<?php
if (is_user_logged_in()) {

    if (defined('DEBUG') && DEBUG) {
        global $bp;
        $redirection_url = $bp->loggedin_user->domain;
        wp_redirect($redirection_url);
        exit;
    }
    bp_core_load_template(apply_filters('bp_core_template_plugin', 'index'));
}
?>   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Central Community</title>
        <!-- 
        <link href="<?php bloginfo('stylesheet_directory'); ?>/assets/css/login_page.css" rel="stylesheet" type="text/css" />
         -->
         <link rel='stylesheet' id='bootstrap-css'  href='/cecommunity/wp-content/themes/firmasite/assets/themes/united/bootstrap.min.css?ver=3.8.1' type='text/css' media='all' />
         <link rel='stylesheet' id='firmasite-style-css'  href='/cecommunity/wp-content/themes/firmasite/style.css?ver=3.8.1' type='text/css' media='all' />
    </head>
<?php flush(); ?>

    <!-- Check if user is already logged-in, then redirect to profile page -->

    <body>
        <div id="wrapper">
            
            <style>
            #nav-main {min-width:1024px}
           .navbar-nav {
				background-color: #dd4814;
				border-color: #bf3e11;
			}
			ul.navbar-nav  {height:35px}
			ul.navbar-left {width: 40%}
			ul.navbar-right {width: 60%}
			
			ul#menu-topbar label, ul#menu-topbar input {display:inline}
			ul#menu-topbar label {padding-left: 5px; padding-right: 10px}
			ul#menu-topbar input  {width:120px; margin: 5px; line-height: 15px; }
			ul#menu-topbar input[type=checkbox] {width: auto; margin:1px}
			ul#menu-topbar button {margin-top: 6px; padding:1px 5px }
			ul#menu-topbar li.lost_pw a {color:#444; font-size:11px; line-height:12px; padding: 5px 2px 6px 1px;}
			
			ul.platform-feat {padding-left:5px}
			ul.platform-feat li {
				font-size:20px;
				margin-bottom:15px; 
				list-style: none;
				line-height:50px; 
				padding: 0px 0px 0px 65px; 
				background: url() no-repeat left top
			}
			
			.platform-reg {width: 210px}
			.platform-reg button {float: right; margin-right:4px}
			
			#footer {
				width: 960px;
				height: auto;
				padding-top: 80px;
				margin: 0 auto;
				color: #666;
			}
			#footer_logos {
				border: solid 0px #ddd;
				border-top-width: 1px;
				padding-top:10px;
			}
			#footer_logos img { padding: 5px}
			</style>
			            
            <div id="nav-main" class="" >
            	<form method="post" id="searchform" action="<?php echo site_url('wp-login.php', 'login_post') ?>">
            		<ul class="nav navbar-nav navbar-left">
            			<li style="width:100%"> &nbsp; </li>
            		</ul>
	                <ul id="menu-topbar" class="nav navbar-nav navbar-right">
	                	<li><label for="un">Username</label><input id="un" type="text" class="username" value="" name="log"></li>
	                	
	                	<li><label for="pw">Password</label><input id="pw" type="password" class="password" value="" name="pwd"></li>
	                	
	                	<li>
	                		<button class="btn-sm" title="Login">Login</button>
	                		
	                		<input id="rcb"  type="checkbox" class="checkbox" name="rememberme" value="forever" />
	                		<label for="rcb"> Remember Me </span>
	                	</li>
	                	
	                	<li class="lost_pw">
	                		<a href="<?php echo site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ?>">Lost your<br>password ?</a>
	                	</li>
	                	
	                	<li style="width:30px">&nbsp;</li>
	             	</ul>
	             </form>
             </div>
            
            
            
            <div class="container">
            	
            		<div style="height:30px">
            			<img width="1000" height="111" style="opacity: 0.5;" src="/cecommunity/wp-content/uploads/2014/07/logo_big.jpg" />
            		</div>
            
            	<div class="col-md-8">
            		
            		<h2>On our platform you can:</h2><br>
            		<ul class="platform-feat">
						<li style="background-image: url(/cecommunity/wp-content/uploads/2014/03/LifeSciences-Network2.png)">Be part of the first European Life Sciences network</li>
						<li style="background-image: url(/cecommunity/wp-content/uploads/2014/03/Partners2.png)">Find the partners you need</li>
						<li style="background-image: url(/cecommunity/wp-content/uploads/2014/03/RD_innovation_projects2.png)">Set-up your R&D or innovation projects</li>
						<li style="background-image: url(/cecommunity/wp-content/uploads/2014/03/ToolsFacilities2.png)">Find or rent tools and facilities</li>
						<li style="background-image: url(/cecommunity/wp-content/uploads/2014/03/Licences2.png)">Buy or sell licences</li>
					</ul>
            		
            	</div>
            	<div class="col-md-1"></div>
            	<div class="col-md-3">
            		<h3>New to the platform ?</h3>
            		<br>
            		<form method="post" action="<?php echo site_url('/register/') ?>"  class="platform-reg">
            			<div class="form-group">
            				<input name="signup_username" type="text" placeholder="Username" autocomplete="off" />
            			</div>
            			<div class="form-group">
            			<input name="signup_email" type="text" placeholder="Email"  autocomplete="off" />
            			</div>
            			<button class="btn btn-primary btn-default">Register</button>
            		</form>
            	
            	</div>
            
            </div>
   
			 <div id="footer">
				<div id="footer_logos">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/footer_logo01.png"  />
					<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/footer_logo02.png"  />
					<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/footer_logo03.jpg" width="43" height="36" />
				</div>		
				 <div id="footer_text">
					<span class="copyright">Copyright © 2012 COLLECTIVE CENTRAL EUROPE programme | The Central Community project is implemented through the CENTRAL EUROPE programme co-financed by the ERDF.</span><br />
		         </div>
	         </div>
        </div>                                    
		<script type='text/javascript' src='/cecommunity/wp-includes/js/jquery/jquery.js?ver=1.10.2'></script>
		
		<?php /*do_action('wp_footer')*/ ?>
	</body>
</html>
