#!/bin/bash
SQLID="root"
SQLPASS="shipped!!"
export MYSQL_PWD=$SQLPASS

if [ ! -e /var/log/mysql.pipe ];
then
mkfifo /var/log/mysql.pipe
fi
while [ -e /var/log/mysql.pipe ]
do
mysql -u$SQLID syslog < /var/log/mysql.pipe > /dev/null
done
