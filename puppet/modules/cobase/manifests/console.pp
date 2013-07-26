################################################################################
# Symfony Console Helper
# https://github.com/vagrantee/puppet-symfony/blob/master/manifests/console.pp
################################################################################

define cobase::console (
  $doc_root = '/vagrant',
  $options  = ""
  ) {

  exec { "${name}":
    command => "php ${doc_root}/app/console ${name} ${options}",
  }
}