# Symfony Starter

Used to create new projects using [Symfony](http://symfony.com/) at XM Media.

## Setting Up a New Site

1. Download a copy of this repo (probably as a ZIP).
2. [Install Composer](https://getcomposer.org/download/)
3. `composer.json` changes:
  - update the `name`, `license` and `description`
4. Set the timezone in `app/AppKernel.php`.
5. Composer update: `php composer.phar update --no-autoloader --no-scripts`
6. Find and make changes near `@todo-symfony` comments throughout the site.
7. Setup server:
  1. Upload the files (exclude files that are OS dependent like `node_modules` & `app/config/parameters.yml` or that are only for editing like `.idea` and a lot of what's in `.gitignore`).
  2. Create the database.
  4. [Install Composer](https://getcomposer.org/download/).
  3. Run `. ./deploy.sh` (this will setup node & gulp and run gulp & composer). It will ask for the parameter values including database & SMTP. A secret can be retrieved from http://nux.net/secret
  5. `mkdir app/cache app/logs & chmod 0777 app/{cache,logs}`
  6. Set FACLs as root (see below).
  7. Create a user `php app/console fos:user:create` and then promote them (add the role `ROLE_SUPER_ADMIN`) `php app/console fos:user:promote`
  8. Setup mail spooler: add cron task similar to: `* * * * * cd /<path> && php app/console swiftmailer:spool:send --message-limit=10 --time-limit=45 >> app/logs/mailer.log`

**Dev site can be accessed at https://[domain]/app_dev.php/**


**Set FACLs**
```
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1\`
setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```