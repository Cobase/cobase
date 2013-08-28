$script = <<SCRIPT
if ! egrep -q '^COMPOSER_HOME' /etc/environment; then
  echo 'COMPOSER_HOME="/home/vagrant"' >> /etc/environment
fi
SCRIPT

Vagrant.configure("2") do |config|

  config.vm.box = "precise64-guest-additions"
  config.vm.box_url = "http://dl.dropbox.com/u/1537815/precise64.box"

  config.vm.network :private_network, ip: "192.168.33.101"
  config.ssh.forward_agent = true

  config.vm.provider :virtualbox do |v|
    v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    v.customize ["modifyvm", :id, "--memory", 2048]
    v.customize ["modifyvm", :id, "--name", "cobase"]
  end

  config.vm.synced_folder "./", "/vagrant", id: "vagrant-root", :nfs => true

  config.vm.provision :shell, :inline => $script

  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = "puppet/manifests"
    puppet.module_path = "puppet/modules"
    puppet.options = ['--verbose']
  end
end
