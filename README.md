# hr_cloud_computing

#!/bin/bash
sudo yum update -y
sudo yum install git -y
git clone https://github.com/rjpoh/hr_cloud_computing.git
-----------------------------------------------------------------
#User Data
#!/bin/bash -ex
# Updated to use Amazon Linux 2
sudo yum -y update
sudo yum -y install httpd php mysql php-mysql
sudo amazon-linux-extras install -y lamp-mariadb10.2-php7.2 php7.2
sudo yum install -y httpd mariadb-server
sudo /usr/bin/systemctl enable httpd
sudo /usr/bin/systemctl start httpd
cd /var/www/html

----------------------------------------------------------------
#to get html directory
wget https://aws-tc-largeobjects.s3.amazonaws.com/CUR-TF-100-ACCLFO-2/lab5-rds/lab-app-php7.zip
unzip lab-app-php7.zip -d /var/www/html/
chown apache:root /var/www/html/rds.conf.php

----------------------------------------------------------------
#install sql
sudo rpm -Uvh http://dev.mysql.com/get/mysql-community-release-el7-5.noarch.rpm
yum install mysql-server

mysql -u main -P 3306 --host assignmentdb.cstcxmslvisd.us-east-1.rds.amazonaws.com -p

----------------------------------------------------------------
Directory 
cd /var/www/html

-----------------------------------------------------------------
cd ~
sudo curl -sS https://getcomposer.org/installer | sudo php
sudo mv composer.phar /usr/local/bin/composer
sudo ln -s /usr/local/bin/composer /usr/bin/composer

--------------------------------------------------------------------
sudo composer install


cd /home/ec2-user
nano composer.json


{ "require": { "monolog/monolog": "1.0.*" } }
