Cobase
======

Cobase is an open source alternative to corporate social media portals such as Yammer and Moovia. It allows staff to 
exchange information by creating groups and posting new messages to them. No more sending mass email to the whole 
company, instead direct your messages to the group of people that actually are interested in the content.

Each user can subscribe to those groups that are of his or her interest. For example, a developer can subscribe to a group 
called 'PHP development news'. When someone posts to that group, he/she will receive the same message on the front page's 
summary section, showing all posts from the groups the user has subscribed to. This way, people won't get distracted by 
messages not related to their work or interest.

Cobase has a fully responsive layout that works on smart phones, tablet PCs and computer screen.

http://cobase.featurice.com/screenshots/screen1.png
http://cobase.featurice.com/screenshots/screen2.png
http://cobase.featurice.com/screenshots/screen3.png
http://cobase.featurice.com/screenshots/screen4.png
http://cobase.featurice.com/screenshots/screen5.png

#Requirements

- PHP 5.3 or later
- MySQL
- Symfony 2

#Current features

- User management with FOSUserBundle: Login, Registration, Confirmation, Password retrieval
- ACL for entities
- Gravatar implementation for using centralized avatars
- Users can create groups
- Users can post to groups
- Users can modify or delete their own posts
- Users can subscribe/unsubscribe to groups
- Each user has their own wall with all posts from the groups they have subscribed to in chronological order
- Nice user interface with clear visual representation of categories
- Creator of a post can edit or move the post
- Google Analytics implementation

#Upcoming features

You are more than welcome to join us to make Cobase even better. Please rever to the issues list to see what is coming up and if you would be able to pitch in.

https://github.com/CoBase/cobase/issues

# Travis CI status

[![Build Status](https://secure.travis-ci.org/CoBase/cobase.png)](https://travis-ci.org/CoBase/cobase)

#Installation

Fork the project into your Github account and then clone it into your development environment.

    $ git clone git@github.com:YOUR_GIT_ACCOUNT_NAME/cobase.git

Now go to your newly created directory.

Copy the distribution file for the parameters to your local file:

    $ cp app/config/parameters.yml-dist app/config/parameters.yml

Modify the parameters.yml to reflect your database connections and smtp settings.

Create the following directories inside app:

- cache
- logs

Prepare cache and logs folder permissions by running (double check your apache user on the first one):

    $ sudo chmod -Rf +a "daemon allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
    $ sudo chmod -Rf +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    $ curl -s https://getcomposer.org/installer | php

Then, use the `install` command to install all dependancies:

    $ php composer.phar install

After all dependancies are installed, make sure your app/cache and app/logs
folder have write access. If there is no write access, the web server might
output an internal error.

Connect to your database and run these commands:

    CREATE USER 'cobase'@'localhost' IDENTIFIED BY 'secret';
    GRANT ALL PRIVILEGES ON *.* TO 'cobase'@'localhost';

then run the following commands:

    $ app/console doctrine:database:create

#Database migrations

This app comes bundled with Doctrine Migrations bundle, which simplifies the
process of keeping database structure in sync with multiple developers and
production environment.

Migrations bundle checks the structure of your entities and does it's magic
based on that information.

After you have created the database as stated in previous section, you need to create schema into
the database. Since we are using Doctrine migrations, we use the console tool to create the schema.

    $ app/console doctrine:migrations:migrate

New migration scripts appear when you pull new code from Github. To see if there
are any new migrations available, you need to check the status.

    $ app/console doctrine:migrations:status

If you see new migrations available, all you have to do is run the migrations.

    $ app/console doctrine:migrations:migrate

You should now have your database in an updated state with up-to-date structure
that corresponds with application's entity classes.

#Initializing database

If you wish to erase data in the database and create a new fresh instance of
database with dummy data and three users, run the fixtures command:

    $ app/console doctrine:fixtures:load

NOTE: This will erase all data and create new dummy data. However, this process
will not recreate the structure of the database. If you wish to update schema before
you run fixtures, always run the Doctrine migrations tool.

Loading fixtures as described earlier, three user account are created: dev1, dev2 and dev3.
Passwords for these users are the same as the usernames respectively.

#Assets

Cobase uses asset management and thus, you must dump the assets.

    $ app/console assetic:dump

During development it might be useful to use this version so that it actively listens for ny changes and builds the assets
automatically:

    $ app/console assetic:dump --watch (--force)

#JavaScript routes:

Available routes needs to be provided for the frontend too:

    $ app/console fos:js-routing:dump

#Google Analytics

This application comes bundled with Google Bundle by antimattr/GoogleBundle. You can configure
your Google Analytics parameters in app/config/google.yml file. By default, Google Analytics is
disabled. You need to enable it by changing enable_google_analytics parameter to true in 
app/config/parameters.yml file.

For more features of Google Bundle, refer to https://github.com/antimattr/GoogleBundle

#Architecture

Application has a DemoBundle, which is a first revision of suggestion for the
application's architecture.

#Testing

We strongly encourage you to practice test driven development and write those
unit tests for the code you make. As we have multiple developers involved,
it is crucial that we make sure the application code is working.

To run a test, go to your project's folder and run following command:

    $ phpunit -c app src/Cobase

#Contributing

Anyone is free to join the team and develop Cobase. Let me know what issue from the Github's 
issue list you are willing to work on.

Enjoy and welcome to the project!

Artur Gajewski

info@arturgajewski.com

Skype: artur.t.gajewski
