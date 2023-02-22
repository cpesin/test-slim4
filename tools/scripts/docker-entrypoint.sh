#!/bin/bash
set -e

echo ::: $(date) :::
echo :::::::::::::::: DUMP ENV :::::::::::::::::
echo :::::::::::::::::::::::::::::::::::::::::::
APP_ENV=${APP_ENV}

echo "DUMP ENV successfully"


### CREATE DIRECTORIES / SET PERMISSIONS ###
echo ::: $(date) :::
echo :::::::::::::::: SET PERMISSIONS ::::::::::::::::::::::::
echo :::::::::::::::::::::::::::::::::::::::::::::::::::::::::
mkdir -p ${WEB_FOLDER}/var/cache ${WEB_FOLDER}/var/log ${WEB_FOLDER}/public ${WEB_FOLDER}/vendor
chown -R 1000:www-data ${WEB_FOLDER}/var ${WEB_FOLDER}/public ${WEB_FOLDER}/vendor
chmod -R 775 ${WEB_FOLDER}/var

echo "PERMISSIONS SET successfully"


### RUN APACHE
bash apache2-foreground


echo :::::::::::::::: FINISHED ::::::::::::::::::::
