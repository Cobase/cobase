Cobase
======

The following is a step-by-step checklist of things you need to do when initially installing Cobase to your development environment.

#Steps

$ cd /your_workspace_root

$ git clone git@github.com:YOUR_GIT_ACCOUNT_NAME/cobase.git

$ cd cobase

$ cp app/config/parameters.yml-dist app/config/parameters.yml

$ mkdir app/cache

$ mkdir app/logs

$ sudo chmod -Rf +a "daemon allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs

$ sudo chmod -Rf +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs

$ curl -s https://getcomposer.org/installer | php

$ php composer.phar install

$ app/console doctrine:database:create

$ mysql

	CREATE USER 'cobase'@'localhost' IDENTIFIED BY 'secret';
    GRANT ALL PRIVILEGES ON *.* TO 'cobase'@'localhost';
    
$ app/console doctrine:database:create

$ app/console doctrine:migrations:migrate

$ app/console assetic:dump

$ app/console fos:js-routing:dump

$ phpunit -c app