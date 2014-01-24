CECommunity
==========

The central community project


*************
  IMPORTANT
*************

The following settings are necessary in order project to work properly on
the web server.


[Apache/Httpd configuration] - .htaccess

/etc/apache2/sites-enabled/000-default or /etc/httpd/conf/httpd.conf

        <Directory /var/www/ccommunity/>
          Options Indexes FollowSymLinks
          AllowOverride All
          Order allow,deny
          Allow from all
        </Directory>
        
