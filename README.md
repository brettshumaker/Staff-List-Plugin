Staff-List-Plugin
=================

This plugin is in a state of active development as we are currently rewriting it from scratch. Check out the [latest release](https://github.com/brettshumaker/Staff-List-Plugin/releases) for the latest stable version.

## Installation

Download from https://wordpress.org/plugins/simple-staff-list and upload the zip via the WordPress admin "Plugins" page.

## Local Development

Want to test out Simple Staff List and work on it? Here's how you can set up your own
testing environment in a few easy steps:

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
2. Grab a copy of Simple Staff List:
   ```bash
   git clone git@github.com:brettshumaker/Staff-List-Plugin.git
   ```
3. ```bash
   cd Staff-List-Plugin
   ```
4. ```bash
   ./local/bin/start.sh
   ```

You're done! You should now have a WordPress site available at
http://localhost:8090/.

To access the admin interface, visit http://localhost:8090/wp-admin/ and log
in with the credentials below:

   ```
   Username: admin
   Password: admin
   ```

## Issue Tracking

All tickets for the project are being tracked on [GitHub](https://github.com/brettshumaker/simple-staff-list/issues).


<p align="right"><a href="https://wordpress.org/plugins/simple-staff-list/"><img src="https://img.shields.io/wordpress/plugin/dt/simple-staff-list?label=wp.org%20downloads&style=for-the-badge">&nbsp;<img src="https://img.shields.io/wordpress/plugin/stars/simple-staff-list?style=for-the-badge"></a></p>
