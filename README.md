Staff-List-Plugin
=================

This plugin is in a state of active development as we are currently rewriting it from scratch. Check out the `master` branch for the latest stable version.

## Installation

Copy the `trunk` folder in your `wp-content` folder, rename `trunk` to `simple-staff-list`, and activate the plugin.


## Quick Setup

Want to test out Simple Staff List and work on it? Here's how you can set up your own
testing environment in a few easy steps:

1. Install [Vagrant](http://vagrantup.com/) and [VirtualBox](https://www.virtualbox.org/).
2. Clone [Chassis](https://github.com/Chassis/Chassis):

   ```bash
   git clone --recursive git@github.com:Chassis/Chassis.git simple-staff-list-tester
   ```
   
   If you're getting a `permission denied` error, it probably means you need to set up your [GitHub SSH Key](https://help.github.com/articles/generating-ssh-keys/).

3. Grab a copy of Simple Staff List:

   ```bash
   cd simple-staff-list-tester
   mkdir -p content/plugins content/themes
   cp -r wp/wp-content/themes/* content/themes
   git clone git@github.com:brettshumaker/Staff-List-Plugin.git simple-staff-list
   ```

4. Start the virtual machine:

   ```bash
   vagrant up
   ```

5. Create a symlink and activate the plugin:

   ```bash
   vagrant ssh -c 'cd /vagrant && ln -s /vagrant/simple-staff-list/trunk /vagrant/content/plugins/simple-staff-list && wp plugin activate simple-staff-list'
   ```

You're done! You should now have a WordPress site available at
http://vagrant.local.

To access the admin interface, visit http://vagrant.local/wp/wp-admin and log
in with the credentials below:

   ```
   Username: admin
   Password: password
   ```

<!-- ### Testing

For testing, you'll need a little bit more:

1. Clone the [Tester extension](https://github.com/Chassis/Tester) for Chassis:

   ```bash
   # From your base directory, api-tester if following the steps from before
   git clone --recursive https://github.com/Chassis/Tester.git extensions/tester
   ```

2. Run the provisioner:

   ```
   vagrant provision
   ```

3. Log in to the virtual machine and run the testing suite:

   ```bash
   vagrant ssh
   cd /vagrant/content/plugins/simple-staff-list
   phpunit
   ```

   You can also execute the tests in the context of the VM without SSHing
   into the virtual machine (this is equivalent to the above):

   ```bash
   vagrant ssh -c 'cd /vagrant/content/plugins/simple-staff-list && phpunit'
   ``` -->


## Issue Tracking

All tickets for the project are being tracked on [GitHub](https://github.com/brettshumaker/simple-staff-list/issues).
