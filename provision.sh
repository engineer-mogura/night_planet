# Apache、git、unzipのインストール
sudo yum -y install httpd unzip git

# PHPのインストール。Epel, Remiリポジトリから行います。
sudo yum -y install epel-release
sudo yum -y install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
sudo yum -y install --enablerepo=remi,remi-php71 php php-devel php-mbstring php-intl php-mysql php-xml

# MySQLのインストール
sudo yum -y install http://dev.mysql.com/get/mysql-community-release-el6-5.noarch.rpm
sudo yum -y install mysql-community-server

# Cakephpコンポーザーのダウンロートとコンポーザーを/bin下に移動
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# ApcheをVagrant起動時に起動するように設定と起動
sudo systemctl enable httpd.service
sudo systemctl start httpd.service

# Vagrantの共有フォルダにパスを設定
sudo rm -rf /var/www/html
sudo ln -fs /vagrant /var/www/html

#MySQLをVagrant起動時に起動するように設定と起動
sudo systemctl enable mysqld.service
sudo systemctl start mysqld.service