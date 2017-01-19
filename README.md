# Symfony Starter

Used to create new projects using [Symfony](http://symfony.com/) at [XM Media](https://www.xmmedia.com/).

## Setting Up a New Site

1. Download a copy of this repo (probably as a ZIP).
2. Remove or update the `LICENSE` file.
2. [Install Composer](https://getcomposer.org/download/) locally.
3. `composer.json` changes:
  - update the `name`, `license` and `description`
4. Update `package.json`
5. Composer install & update (locally, no autoloader or scripts): `php composer.phar install --no-autoloader --no-scripts && php composer.phar update --no-autoloader --no-scripts`
6. Run `yarn && yarn upgrade` locally (may not be needed).
7. Find and make changes near `@todo-symfony` comments throughout the site.
8. Setup server:
  1. Upload the files (exclude files that are OS dependent like `node_modules` & `app/config/parameters.yml` or that are only for editing like `.idea` and a lot of what's in `.gitignore`).
  2. [Install Composer](https://getcomposer.org/download/)
  3. Install PHP packages/vendors: `php composer.phar install` It will ask for the parameter values including database & SMTP. A secret can be retrieved from http://nux.net/secret
  4. Install NVM: https://github.com/creationix/nvm#install-script
  5. Run `. ./node_setup.sh` (this will setup node & gulp).
  6. Run `yarn` to install Node packages.
  7. Run `gulp --dev` to compile JS & CSS files.
  8. Create the database: `php bin/console doctrine:schema:create`
  9. `mkdir var && chmod -R 0777 var`
  10. Set FACLs as root (if needed, see below).
  11. Create a user `php bin/console fos:user:create` and then promote them (add the role `ROLE_SUPER_ADMIN`) `php bin/console fos:user:promote`
  12. Setup mail spool: add cron task similar to: `* * * * * cd <path> && php bin/console swiftmailer:spool:send --message-limit=10 --time-limit=45 >> var/logs/mailer.log`
9. Delete starter files: `README.md` (or update), `TEMPLATES.md`.

**Dev site can be accessed at https://[domain]/app_dev.php/**

**Set FACLs**
```
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
```

## System Requirements

  - PHP 5.6 or 7.1+
  - MySQL 5.6+
  - [Yarn](https://yarnpkg.com/en/docs/install)