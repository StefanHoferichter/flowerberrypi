#!/bin/bash
set -e
set -o pipefail

echo "Starting installation of flowerberrypi"

# --- Variablen ---
LARAVEL_PATH="/var/www/html/flowerberrypi"
PYTHON_PATH="/opt/myapp/python"

read -rsp "Please enter mysql root password: " DB_PASS

# --- Root Check ---
if [[ $EUID -ne 0 ]]; then
   echo "❌ please run as root: sudo install.sh"
   exit 1
fi

echo "Installing raspbian packages"
apt-get update
apt-get install -y git build-essential supervisor net-tools proftpd \
        php-common libapache2-mod-php php-cli mc openssl ssl-cert \
		apache2 mariadb-server python3-pip python3-dev pigpio i2c-tools \
		php-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd \
		python3-pip python3-dev libgpiod-dev python3-lgpio

echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password $DB_PASS" | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password $DB_PASS" | debconf-set-selections
apt-get install -y phpmyadmin

echo "⚙️  activating pigpiod …"
systemctl enable pigpiod
systemctl start pigpiod

echo "installing 433Utils"

REAL_USER="${SUDO_USER:-$(whoami)}"
echo "the real user is: $REAL_USER"

rm -rf 433Utils
rm -rf WiringPi
sudo -u "$REAL_USER" git clone --recursive https://github.com/ninjablocks/433Utils.git
sudo -u "$REAL_USER" git clone https://github.com/WiringPi/WiringPi.git
cd WiringPi
sudo -u "$REAL_USER" ./build
cd ../433Utils/RPi_utils
sudo -u "$REAL_USER" make

echo "compiling 433mhz sender for raspberry pi 5"
cd /var/www/html/flowerberrypi/app/c
gcc send_433mhz_pi5.c -o send433_pi5 -lgpiod
sudo -u "$REAL_USER" cd ~

echo "installing adafruit-circuitpython-dht"
pip install adafruit-circuitpython-dht --break-system-packages 

echo "installing pigpio-dht"
pip install pigpio-dht --break-system-packages

echo "installing adafruit"
pip install adafruit-blinka adafruit-circuitpython-ads1x15==2.4.4 --break-system-packages

echo "rpi-rf"
pip install rpi-rf --break-system-packages 

echo "installing composer"
sudo -u "$REAL_USER" wget -O composer-setup.php https://getcomposer.org/installer
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
mkdir -p /var/www/.config/composer
chown -R www-data:www-data /var/www/.config
mkdir -p /var/www/.cache/composer
chown -R www-data:www-data /var/www/.cache/composer

echo "creating laravel project"
cd /var/www/html
rm -rf flowerberrypi
mkdir flowerberrypi
chown -R www-data:www-data flowerberrypi
cd flowerberrypi
sudo -u www-data composer config --global process-timeout 600
sudo -u www-data composer create-project laravel/laravel .
sudo -u www-data composer require Laravel/breeze
chmod -R 777 storage
sudo -u www-data php artisan breeze:install blade --no-interaction

echo "downloading flowerberrypi sources"
cd /var/www/html/
rm -rf temp
mkdir temp
cd temp
git clone https://github.com/StefanHoferichter/flowerberrypi.git .
cd ..
chown -R www-data:www-data temp

echo "deploying flowerberrypi sources"
SOURCE_DIR="/var/www/html/temp"
DEST_DIR="/var/www/html/flowerberrypi"
cp -r "$SOURCE_DIR"/app/* "$DEST_DIR"/app/
cp -r "$SOURCE_DIR"/bootstrap/* "$DEST_DIR"/bootstrap/
cp -r "$SOURCE_DIR"/config/* "$DEST_DIR"/config/
cp -r "$SOURCE_DIR"/database/* "$DEST_DIR"/database/
cp -r "$SOURCE_DIR"/public/* "$DEST_DIR"/public/
cp -r "$SOURCE_DIR"/resources/* "$DEST_DIR"/resources/
cp -r "$SOURCE_DIR"/routes/* "$DEST_DIR"/routes/

echo "configuring flowerberrypi project"
cp "$SOURCE_DIR"/env/.env.sample "$DEST_DIR"/.env

echo "configuring apache 2"
a2enmod rewrite
cp "$SOURCE_DIR"/env/flowerberrypi.conf /etc/apache2/sites-available/
a2dissite 000-default.conf
a2ensite flowerberrypi.conf
systemctl reload apache2

echo "configuring database"
update_env_var() {
    local key="$1"
    local value="$2"
    local file="$3"

    if grep -q "^${key}=" "$file"; then
        sed -i "s|^${key}=.*|${key}=${value}|" "$file"
    else
        echo "${key}=${value}" >> "$file"
    fi
}
echo "set root pw"
mysql <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED BY '$DB_PASS';
flush privileges;
EOF
echo "create flowerberrypi database"
mysql -u root -p"$DB_PASS" <<EOF
DROP DATABASE IF EXISTS flowerberrypi;
CREATE DATABASE IF NOT EXISTS flowerberrypi;
GRANT ALL PRIVILEGES ON flowerberrypi.* TO root@localhost;
GRANT ALL PRIVILEGES ON flowerberrypi.* TO phpmyadmin@localhost;
flush privileges;
EOF
update_env_var "DB_PASSWORD" "$DB_PASS" "$DEST_DIR"/.env

echo "populate flowerberrypi database"
cd "$DEST_DIR"
chmod -R 777 /var/www/html/flowerberrypi/storage
chmod -R 777 /var/www/html/flowerberrypi/bootstrap
sudo -u "$REAL_USER" php artisan migrate:refresh --seed

sudo -u www-data php /var/www/html/flowerberrypi/artisan tinker --execute="Log::info('Installation Log erstellt');"
chmod -R 777 "$DEST_DIR"/storage/logs/laravel.log

echo "deploy 433Utils"
cp /home/"$REAL_USER"/433Utils/RPi_utils/codesend "$DEST_DIR"/app/python
cp /home/"$REAL_USER"/433Utils/RPi_utils/RFSniffer "$DEST_DIR"/app/python

echo "configure group memberships"
usermod -aG i2c www-data
usermod -aG video www-data
usermod -aG gpio www-data

echo "configure sudoers"
chmod 440 "$SOURCE_DIR"/env/sudoers
chown root:root "$SOURCE_DIR"/env/sudoers
cp "$SOURCE_DIR"/env/sudoers /etc

echo "configure crontab"
chmod 644 "$SOURCE_DIR"/env/crontab
chown root:root "$SOURCE_DIR"/env/crontab
cp "$SOURCE_DIR"/env/crontab /etc

echo "configure laravel worker"
cp "$SOURCE_DIR"/env/laravel-worker.conf /etc/supervisor/conf.d

echo "✅ Installation finalized!"
