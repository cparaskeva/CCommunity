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

        <Directory /var/www/cecommunity/>
<<<<<<< HEAD
          Options Indexes FollowSymLinks
          AllowOverride All
          Order allow,deny
          Allow from all
=======
          AllowOverride None
          php_flag display_errors on

          RewriteEngine On
          RewriteBase /cecommunity/
          RewriteRule ^index\.php$ - [L]

          # uploaded files
          RewriteRule ^([_0-9a-zA-Z-]+/)?files/(.+) wp-includes/ms-files.php?file=$2 [L]

          # add a trailing slash to /wp-admin
          RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]

          RewriteCond %{REQUEST_FILENAME} -f [OR]
          RewriteCond %{REQUEST_FILENAME} -d
          RewriteRule ^ - [L]
          RewriteRule  ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]
          RewriteRule  ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
          RewriteRule . index.php [L]
>>>>>>> CECommunity
        </Directory>
        
