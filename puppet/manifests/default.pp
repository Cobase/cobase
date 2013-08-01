/*********************************************************************
* COBASE DEV BOX
**********************************************************************/

$doc_root        = "/vagrant/web"
$mysql_host      = 'localhost'
$mysql_db        = 'cobase'
$mysql_user      = 'cobase'
$mysql_pass      = 'd]20Px564g'
$pma_port        = 8000
$php_modules     = [ 'xdebug', 'curl', 'mysql', 'cli', 'intl', 'mcrypt', 'memcache']
$sys_packages    = [ 'git', 'curl', 'vim']

Exec { path => [ "/bin/", "/sbin/" , "/usr/bin/", "/usr/sbin/" ] }

exec { 'apt-update':
  command => 'apt-get update',
}

Exec["apt-update"] -> Package <| |>

class { 'apt': }

package { ['python-software-properties']:
  ensure  => 'installed',
}

package { $sys_packages:
  ensure => "installed",
}

class { "apache": }

apache::module { 'rewrite':}

apache::vhost { 'default':
  docroot             => $doc_root,
  server_name         => false,
  priority            => '',
  template            => 'cobase/apache/vhost.conf.erb',
}

#apt::ppa { 'ppa:ondrej/php5-oldstable':
#  before  => Class['php'],
#}

class { 'php':
  package_devel => 'php5-dev'
}

php::module { $php_modules:
  require => Class[ 'php' ]
}

# php-apc, non-standard prefix
php::module { 'apc':
  module_prefix => "php-",
  require => Class[ 'php' ]
}

php::ini { 'php':
  value  => ['date.timezone = "UTC"','upload_max_filesize = 8M', 'short_open_tag = 0'],
  target => 'php.ini'
}

php::pear::module { 'phpunit':
  use_package => false,
  alldeps     => true
}

class { 'mysql':
  root_password => 'root',
}

mysql::grant { $mysql_db:
  mysql_privileges     => 'ALL',
  mysql_db             => $mysql_db,
  mysql_user           => $mysql_user,
  mysql_password       => $mysql_pass,
  mysql_host           => $mysql_host,
  mysql_grant_filepath => '/home/vagrant/puppet-mysql',
}

package { 'phpmyadmin':
  require => Class[ 'mysql' ],
}

apache::vhost { 'phpmyadmin':
  server_name => false,
  docroot     => '/usr/share/phpmyadmin',
  port        => $pma_port,
  priority    => '10',
  require     => Package['phpmyadmin'],
  template    => 'cobase/apache/vhost.conf.erb',
}

class { 'composer':
  require => [ Class[ 'php' ], Package[ 'curl' ] ]
}

composer::install { 'cobase':
  path    => '/vagrant',
  require => Class[ 'composer' ]
}

class {'cobase':
  site_title         => "Cobase",
  allow_registration => true,
  db_host            => $mysql_host,
  db_name            => $mysql_db,
  db_user            => $mysql_user,
  db_pass            => $mysql_pass,
  require            => [ Class[ 'apache' ], Class[ 'php' ] ],
  template           => 'cobase/symfony/parameters.yml.erb',
}

cobase::console { 'doctrine:migrations:migrate':
  require => Composer::Install['cobase']
}

cobase::console { 'doctrine:fixtures:load':
  require => Cobase::Console['doctrine:migrations:migrate']
}

cobase::console { 'assetic:dump':
  options => '--watch',
  require => Composer::Install['cobase']
}

cobase::console { 'fos:js-routing:dump':
  require => Composer::Install['cobase']
}