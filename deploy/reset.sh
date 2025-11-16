#!/bin/bash
set -e
set -o pipefail

REAL_USER="${SUDO_USER:-$(whoami)}"
echo "the real user is: $REAL_USER"

apt purge -y phpmyadmin mariadb-server mariadb-server mariadb-client mariadb-common libmariadb3 mariadb-server-core mariadb-client-core
apt autoremove -y
rm -rf /etc/phpmyadmin
rm -rf /usr/share/phpmyadmin
#rm -rf /etc/mysql
rm -rf /var/cache/debconf/*.dat
rm -rf /home/"$REAL_USER"/433Utils
rm -rf /home/"$REAL_USER"/WiringPi
rm -rf /var/www/html/flowerberrypi
rm -rf /var/www/html/temp
rm -rf /etc/apache2/sites-available/flowerberrypi.conf
rm -rf /etc/supervisor/conf.d/laravel-worker.conf

sudo debconf-communicate phpmyadmin <<EOF
purge
EOF


echo "✅ Deinstallation finalized!"
