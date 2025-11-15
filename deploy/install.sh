#!/bin/bash
set -e
set -o pipefail

echo "Starting installation of flowerberrypi"

# --- Variablen ---
LARAVEL_PATH="/var/www/html/flowerberrypi"
PYTHON_PATH="/opt/myapp/python"

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
		php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd

#echo "📁 Erstelle Ordner …"
#mkdir -p $PYTHON_PATH/scripts
#mkdir -p $PYTHON_PATH/service

#echo "📄 Installiere Python-Skripte …"
#cp -r python/* $PYTHON_PATH/
#pip3 install -r $PYTHON_PATH/requirements.txt

echo "⚙️  activating pigpiod …"
systemctl enable pigpiod
systemctl start pigpiod

#echo "📦 Installiere Laravel Paket …"
#cp -r laravel/package/* "$LARAVEL_PATH"/packages/
#cd "$LARAVEL_PATH"
#composer dump-autoload

#echo "🛠 Setze ENV Variablen falls nötig …"
#if [[ ! -f "$LARAVEL_PATH/.env" ]]; then
#    cp config/env.template "$LARAVEL_PATH/.env"
#fi

#echo "📡 Registriere Systemd-Services …"
#cp systemd/python_daemon.service /etc/systemd/system/
#cp systemd/laravel_queue.service /etc/systemd/system/

#systemctl daemon-reload
#systemctl enable python_daemon
#systemctl enable laravel_queue

#systemctl start python_daemon
#systemctl start laravel_queue

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

echo "installing pigpio-dht"
pip install pigpio-dht --break-system-packages

echo "installing adafruit"
pip install adafruit-blinka adafruit-circuitpython-ads1x15==2.4.4 --break-system-packages

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

echo "configuring apache 2"
a2enmod rewrite
cp "$SOURCE_DIR"/env/flowerberrypi.conf /etc/apache2/sites-available/
a2dissite 000-default.conf
a2ensite flowerberrypi.conf
systemctl reload apache2


echo "✅ Installation finalized!"
