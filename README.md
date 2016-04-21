# Symfony Starter

Used to create new projects using [Symfony](http://symfony.com/) at XM Media.

## Setting Up a New Site

1. Download a copy of this repo (probably as a ZIP).
2. [Install Composer](https://getcomposer.org/download/)
6. `composer.json` changes:
  - update the `name`, `license` and `description`
11. Set the timezone in `app/AppKernel.php`.
13. Composer update: `php composer.phar update --no-autoloader --no-scripts`
14. Find and make changes near `@todo-symfony` comments throughout the site.
16. Setup server:
  1. Upload the files (exclude files that are OS dependent like `node_modules` & `app/config/parameters.yml` or that are only for editing like `.idea` and a lot of what's in `.gitignore`).
  2. Run `. ./node_setup.sh && npm install && gulp`
  3. Create the database.
  4. [Install Composer](https://getcomposer.org/download/) and `php composer.phar install` (it will ask for the parameter values including database & smtp). A secret can be retrieved from http://nux.net/secret
  5. `mkdir app/cache app/logs & chmod 0777 app/{cache,logs}`
  6. Set FACLs (as root): ```
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```
  7. Create a user `php app/console fos:user:create` and then promote them (add the role `ROLE_SUPER_ADMIN`) `php app/console fos:user:promote`

**Dev site can be accessed at https://[domain]/app_dev.php/**


@todo Complete instructions.