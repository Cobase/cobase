####################################################################################
# COBASE PUPPET MODULE - symfony install
# based on https://github.com/vagrantee/puppet-symfony
# @author Erika Heidi<erika@erikaheidi.com>
####################################################################################

class cobase(
  $site_title         = "Cobase",
  $allow_registration = true,
  $login_required     = false,
  $enable_analytics   = false,
  $root_dir           = '/vagrant',
  $template           = 'cobase/parameters.yml.erb',
  $db_driver          = 'pdo_mysql',
  $db_host            = '127.0.0.1',
  $db_port            = '~',
  $db_name            = 'cobase',
  $db_user            = 'cobase',
  $db_pass            = 'cobase',
  $mailer             = 'smtp',
  $mailer_host        = 'localhost',
  $mailer_user        = '~',
  $mailer_pass        = '~',
  $locale             = 'en',
  $secret             = 'somerandomsecretwouldbegreat'
) {

  # change default apache user and groups
  exec { 'ApacheUserGroup':
    command => "sed -i 's/www-data/vagrant/' /etc/apache2/envvars",
    onlyif  => "grep -c 'www-data' /etc/apache2/envvars"
  }

  # change /var/lock/apache2 user and group
  file { "/var/lock/apache2" :
    ensure  => 'directory',
    owner   => "vagrant",
    group   => "vagrant",
    mode    => 0770
  }

  # change cache and log permissions
  file { "${root_dir}/app/cache":
    ensure  => 'directory',
    mode    => 777,
  }

  file { "${root_dir}/app/logs":
    ensure  => 'directory',
    mode    => 777,
  }

  file { "/var/lib/php/session" :
    owner   => "root",
    group   => "vagrant",
    mode    => 0770
  }

  #define the parameters.yml file if it doesnt exists yet
  file { "${root_dir}/app/config/parameters.yml" :
    ensure => 'file',
    content => template($template)
  }
}
