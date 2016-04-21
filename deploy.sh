#!/bin/sh

echo "-- Deploy Setup"
# this will setup nvm so the rest will work
. ~/.nvm/nvm.sh
# used within the PHP install scripts
export SYMFONY_ENV=prod || exit 1

# build commands
. ./node_setup.sh || exit 1
echo "-- npm install"
npm install || exit 1
echo "-- Run Gulp"
gulp || exit 1

# update PHP vendors
echo "-- Update PHP vendors"
php composer.phar install --no-dev --optimize-autoloader --no-interaction --no-progress || exit 1

# run db migrations
echo "-- Run DB Migrations"
php app/console doctrine:migrations:migrate --no-interaction --no-debug || exit 1

# remove some extra files that we don't want on production
echo "-- Remove unwanted public PHP files"
rm -f html/app_dev.php html/config.php || exit 1