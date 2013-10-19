set :stage_dir, 'app/config/deploy'
require 'capistrano/ext/multistage'

set :stages, %w(production staging)

set :application, "Cobase"
set :domain,      "php-updates.com"
set :app_path,    "app"

set :user, "root"

set :repository,  "git@github.com:CoBase/cobase.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web, domain                        # Your HTTP server, Apache/etc
role :app, domain                        # This may be the same as your `Web` server
role :db, domain, :primary => true       # This is where Rails migrations will run

set :keep_releases,  3

set :shared_files, ["app/config/parameters.yml", "composer.phar", "app/cache", "app/logs", "app/data"]
set :use_composer, true

set :update_vendors, true                # Uncomment to run 'composer update' instead of 'composer install'

set :dump_assetic_assets, true

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

namespace :jsroutes do
    desc "Exposing routes to javascripts"
    task :dump do
        run "cd #{release_path} && php app/console fos:js-routing:dump"
    end
end

before "deploy:restart" do
    jsroutes.dump
end