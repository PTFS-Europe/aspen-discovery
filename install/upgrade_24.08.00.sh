#!/bin/sh
if [ -z "$1" ]
  then
    echo "Please provide the server name to update as the first argument."
    exit 1
fi

printf "Updating cron configuration\n"
php /usr/local/aspen-discovery/install/updateCron_24_08.php $1

printf "Updating apache configuration\n"
php /usr/local/aspen-discovery/install/updateApacheConf_24_08.php $1
