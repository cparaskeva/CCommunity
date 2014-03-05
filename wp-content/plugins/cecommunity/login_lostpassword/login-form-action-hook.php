<?php

/**
*  Here, we replace the wordpress logo by the "CECommunity" one
*/
function CeCommLoginForm()
{
$content=ob_get_contents();

// <a href="http://wordpress.org/" title="Powered by WordPress">CECommunity</a>
// =>
//<img src="http://HOST/PATH/wp-content/uploads/2013/12/logo1.png" alt="The central community project!" title="CECommunity" id="logo-img">

$path = bp_get_root_domain();

$content= preg_replace('/<a href="http:\/\/wordpress.org\/" title="Powered by WordPress">CECommunity<\/a>/', 
'<img src="'.$path.'/wp-content/uploads/2013/12/logo1.png" alt="The central community project!" title="CECommunity" id="logo-img">',
$content);

ob_get_clean();
echo $content;
}

add_action( 'login_form', 'CeCommLoginForm' ); // the regular login 
add_action( 'lostpassword_form', 'CeCommLoginForm' ); // the "lost password" one


