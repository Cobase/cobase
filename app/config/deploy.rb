set :stage_dir, 'app/config/deploy'
require 'capistrano/ext/multistage'

set :stages, %w(production testing)

set :application, "YouHighFiveMe"
set :domain,      "youhighfive.me"
set :port,        51510
set :app_path,    "app"

set :user,        "agajewsk"

set :repository,  "git://github.com/YouHighFiveMe/youhighfiveme.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

set  :use_sudo,       false
set  :use_composer,   true

# Set some paths to be shared between versions
set :shared_files,    ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", app_path + "/cache", "vendor"]

set :writable_dirs,     ["app/cache", "app/logs"]
set :webserver_user,    "www-data"
set :permission_method, :acl

task :upload_parameters do
  origin_file = "app/config/parameters.yml"
  destination_file = shared_path + "/app/config/parameters.yml" # Notice the shared_path

  try_sudo "mkdir -p #{File.dirname(destination_file)}"
  top.upload(origin_file, destination_file)
end

after "deploy:setup", "upload_parameters"

