#!/usr/bin/env bash


# Configure Apache
WEBROOT="$(pwd)"
CGIROOT=`dirname "$(which php-cgi)"`
echo "WEBROOT: $WEBROOT"
echo "CGIROOT: $CGIROOT"
sudo echo "<VirtualHost *:80>
         DocumentRoot %TRAVIS_BUILD_DIR%

  <Directory "%TRAVIS_BUILD_DIR%">
    Options FollowSymLinks MultiViews ExecCGI
    AllowOverride All
    Order deny,allow
    Allow from all
  </Directory>

</VirtualHost>" | sudo tee /etc/apache2/sites-available/default > /dev/null
cat /etc/apache2/sites-available/default

sudo a2enmod rewrite
sudo a2enmod actions
sudo service apache2 restart

# Configure custom domain
echo "127.0.0.1 mydomain.local" | sudo tee --append /etc/hosts

echo "TRAVIS_PHP_VERSION: $TRAVIS_PHP_VERSION"