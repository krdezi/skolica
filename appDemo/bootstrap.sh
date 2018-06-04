#!/usr/bin/env bash

# crate swap
sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
sudo /sbin/mkswap /var/swap.1
sudo /sbin/swapon /var/swap.1

sudo apt-get update

#sudo add-apt-repository ppa:ondrej/php
#sudo apt-get update
#sudo apt-get -y dist-upgrade

echo "
   Installing MySql - dbName: skeleton | user:root | password:rootpass
***************************************************************"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password rootpass"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password rootpass"

sudo apt-get install -y mysql-common mysql-server mysql-client
mysql -u root -prootpass  -e "CREATE DATABASE bipsys;"

echo "
   Installing PHP 7.0...
***************************************************************"
sudo apt-get install -y zip unzip imagemagick
sudo apt-get install -y nginx
sudo apt-get install -y curl git redis-server
sudo apt-get install -y php7.0-zip php7.0-fpm php7.0-mcrypt php7.0-curl php7.0-cli php7.0-mysql php7.0-gd php7.0-intl php7.0-xsl
sudo apt-get install -y php-xdebug php7.0-mbstring xpdf php-redis php-imagick

# create config files

if [ ! -f /vagrant/backend/data/phinx/phinx.php ]; then
    cp /vagrant/backend/data/phinx/phinx.php.dist /vagrant/backend/data/phinx/phinx.php
fi

if [ ! -f /vagrant/backend/config/config-local.php ]; then
    cp /vagrant/backend/config/config.php.dist /vagrant/backend/config/config-local.php
fi


cd /vagrant/

#if [ -d "vendor" ]; then
# php composer.phar update
#else
# php composer.phar install
#fi

#cd /vagrant/data/phinx && /vagrant/vendor/bin/phinx migrate

sudo bash -c "echo '
127.0.0.1       localhost
127.0.0.1       bipsys.local

# The following lines are desirable for IPv6 capab
::1     ip6-localhost   ip6-loopback
fe00::0 ip6-localnet
ff00::0 ip6-mcastprefix
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
ff02::3 ip6-allhosts
127.0.1.1       ubuntu-xenial   ubuntu-xenial
' > /etc/hosts"

sudo bash -c "echo 'server {
    listen 80;
    sendfile off;
    root /vagrant/backend/public;
    index index.php index.html index.htm;
    server_name bipsys.local;
    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }
    client_max_body_size 16M;
    client_body_buffer_size 2M;

    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/html;
    }

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param APPLICATION_ENV development;
    }
}' > /etc/nginx/sites-available/bipsysBackend.local"

sudo bash -c "echo 'server {
    listen 80;
    sendfile off;
    root /vagrant/demo/public;
    index index.php index.html index.htm;
    server_name bipsysDemo.local;
    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }
    client_max_body_size 16M;
    client_body_buffer_size 2M;

    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/html;
    }

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param APPLICATION_ENV development;
    }
}' > /etc/nginx/sites-available/bipsysDemo.local"

sudo bash -c "echo '127.0.0.1 bipsys.local' /etc/hosts"

sudo ln -s /etc/nginx/sites-available/bipsysBackend.local /etc/nginx/sites-enabled/bipsysBackend.local
sudo ln -s /etc/nginx/sites-available/bipsysDemo.local /etc/nginx/sites-enabled/bipsysDemo.local
sudo service nginx restart

echo "
   Add this line to hosts file :
   192.168.5.20 bipsysDemo.local
"