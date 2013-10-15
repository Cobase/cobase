#
# OS X Capistrano
#
load 'deploy' if respond_to?(:namespace) # cap2 differentiator
require 'capifony_symfony2'
load 'app/config/deploy'

#
# Linux Capistrano version
#
# require 'capifony_symfony2'
# Dir['vendor/bundles/*/*/recipes/*.rb'].each { |bundle| load(bundle) }
# load 'deploy' if respond_to?(:namespace) # cap2 differentiator
# load Gem.find_files('capifony_symfony2.rb').last.to_s
# load 'app/config/deploy'

#
# Do cleanup after deployment
#
after "deploy:restart", "deploy:cleanup"

