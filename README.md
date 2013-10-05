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

A site at <http://www.php-updates.com> is built on top of Cobase engine and is meant for PHP developers from around the
the world to share news and updates about PHP related things. Feel free to register an account and share what you
know.

Need to have a private Cobase app hosted on your server? You are quite welcome to do so. Just download the source code
and follow few easy steps to have it up and running in no time.

#Requirements

- PHP 5.3 or later
- Symfony2
- MySQL

#Current features

- User management with FOSUserBundle: Login, Registration, Confirmation, Password retrieval
- ACL for entities
- Gravatar implementation for using centralized avatars
- Users can create groups
- Users can post to groups
- Users can modify, move or delete their own posts
- Users can subscribe/unsubscribe to groups
- Each user has their own wall with all posts from the groups they have subscribed to
- Nice user interface with clear visual representation of categories
- Like / Unlike posts
- Commenting on posts
- Each group has an RSS feed
- Option to allow users to browse groups and posts without logging in
- Google Analytics implementation
- Bookmarklet to allow copy main content from any site with just two clicks of a mouse, no more copy/paste.
- Vagrant intergation for setting up development environment in a snap

#Upcoming features

You are more than welcome to join us to make Cobase even better. Please refer to the issues list to see what is coming up and if you would be able to pitch in.
We also welcome new ideas as they are essential to make Cobase what you need it to be.

Current issue list: https://github.com/CoBase/cobase/issues

# Travis CI status

[![Build Status](https://secure.travis-ci.org/CoBase/cobase.png)](https://travis-ci.org/CoBase/cobase)

#Installation

Fork the project into your Github account and then clone it into your development environment.

    $ git clone git@github.com:YOUR_GIT_ACCOUNT_NAME/cobase.git

Now go to your newly created directory.

Copy the distribution file for the parameters to your local file:

    $ cp app/config/parameters.yml-dist app/config/parameters.yml

Modify the parameters.yml to reflect your database connections and smtp settings.

Create the following directories inside app folder:

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

First let's create database based on the values in app/config/parameters.yml file.

    $ app/console doctrine:database:create

After you have created the database, you need to create schema into it. Since we are
using Doctrine migrations, we use the console tool to create the schema from the
migrations files.

    $ app/console doctrine:migrations:migrate

New migration scripts appear when you pull new code from Github. To see if there
are any new migrations required for you to run in your current code version, you need to check the status.

    $ app/console doctrine:migrations:status

If you see new migrations available, all you have to do is run the migrations again.

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

Loading fixtures as described earlier, three user account are created: demo1, demo2 and demo3.
Passwords for these users are the same as the usernames respectively.

#Assets

Cobase uses asset management and thus, you must dump the assets.

    $ app/console assetic:dump

During development it might be useful to use this version so that it actively listens for ny changes and builds the assets
automatically:

    $ app/console assetic:dump --watch (--force)
    
Each time you use your app in the production environment (and therefore, each time you deploy), you should run the following task:

	$ php app/console assetic:dump --env=prod --no-debug

#JavaScript routes:

Available routes needs to be provided for the frontend too:

    $ app/console fos:js-routing:dump

#Google Analytics

This application comes bundled with Google Bundle by antimattr/GoogleBundle. You can configure
your Google Analytics parameters in app/config/google.yml file. By default, Google Analytics is
disabled. You need to enable it by changing enable_google_analytics parameter to true in 
app/config/parameters.yml file.

For more features of Google Bundle, refer to https://github.com/antimattr/GoogleBundle

#Admin users

When you register yourself a user, its role is as ROLE_USER. In order to create administrators, you need to promote a user with the console:

	$ php app/console fos:user:promote [username]
	
Enter ROLE_ADMIN for the user when console prompts for a role. Now login with that user and access to edit/move/delete options are available for groups and posts. 

To remove admin rights from a user, you need to do similar task to demote a user:

	$ php app/console fos:user:demote [username]
	
This time type ROLE_ADMIN to remove that role from this specific username.

#Testing

We strongly encourage you to practice test driven development and write those
unit tests for the code you make. As we have multiple developers involved,
it is crucial that we make sure the application code is working.

To run a test, go to your project's folder and run following command:

    $ phpunit -c app --coverage-text

#Contributing

We welcome any developers with various skills and background. Anyone is free to join the team and develop Cobase.

Current contributors: https://github.com/CoBase/cobase/graphs/contributors 

If you want to join the team, please contact me and provide me with your github account ID so that I can add you to the team.

#IRC

We have our own IRC channel created on freenode.net called #cobase, so you are more than welcome to join the discussion if you have any concerns or interest toward the project.

#Using Vagrant

We have a Vagrant development box ready to use. In order to do so, after cloning the repository you need to run:

    $ git submodule init
    $ git submodule update

Then you will be able to run ``vagrant up``.

The server will run at 192.168.33.101, and PhpMyAdmin will be available at 192.168.33.101:8000 .
For more information on using Vagrant, see our Wiki.

#Welcome

Enjoy and welcome to the project!

Artur Gajewski

info@arturgajewski.com

Skype: artur.t.gajewski
