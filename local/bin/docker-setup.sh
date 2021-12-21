#!/usr/bin/env bash

# Exit if any command fails.
set -e

cli()
{
	docker-compose exec -T -u www-data wordpress "$@"
}

set +e

echo "Downloading WordPress..."

# Check if wp-config.php exits
if [ -f "./docker/wordpress/wp-config.php" ]; then
    echo "wp-config found."
else
    echo "No wp-config found...creating."
    cli wp config create \
    --dbname="${WORDPRESS_DB_NAME}" \
    --dbuser="${WORDPRESS_DB_USER}" \
    --dbpass="${WORDPRESS_DB_PASSWORD}" \
    --dbhost="${WORDPRESS_DB_HOST}" \
	--path=/var/www/html \
    --quiet --skip-check > /dev/null
fi

force_wordpress_container_reconfig=0
for arg in "$@"; do
	if [[ $arg == "--force-reconfig" ]]; then
		force_wordpress_container_reconfig=1
		echo "WordPress container reconfiguration was requested."
	fi
done

# Wait for everything to be started up before the setup.
# The db being accessible means that the db container started and the WP has been downloaded and the plugin linked
cli wp db check --path=/var/www/html --quiet > /dev/null

while [[ $? -ne 0 ]]; do
	echo "Waiting until the service is ready..."
	sleep 5
	cli wp db check --path=/var/www/html --quiet > /dev/null
done
echo "Server DB is up and running"

# Check if we are running an E2E test, and switch ports to test container in case.
WP_LISTEN_PORT=$(docker-compose port wordpress 80 | grep -Eo "[0-9]+$")

set -e

echo
echo "Setting up environment..."
echo

echo "Setting up WordPress..."

cli wp core install \
	--path=/var/www/html \
	--url="localhost:${WP_LISTEN_PORT}" \
	--title="Simple Staff List" \
	--admin_name=admin \
	--admin_password=admin \
	--admin_email=admin@example.com \
	--skip-email \
	--quiet

echo
echo "Setting up logs..."
cli wp config set WP_DEBUG true --raw > /dev/null
cli wp config set WP_DEBUG_LOG 'wp-content/plugins/simple-staff-list/logstash.log' > /dev/null

echo "Updating WordPress to the latest version..."
cli wp core update --quiet

echo "Updating the WordPress database..."
cli wp core update-db --quiet

echo "Configuring WordPress to work with ngrok (in order to allow creating a Jetpack-WPCOM connection)";
cli wp config set DOCKER_HOST "\$_SERVER['HTTP_X_ORIGINAL_HOST'] ?? \$_SERVER['HTTP_HOST'] ?? 'localhost'" --raw
cli wp config set DOCKER_REQUEST_URL "( ! empty( \$_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . DOCKER_HOST" --raw
cli wp config set WP_SITEURL DOCKER_REQUEST_URL --raw
cli wp config set WP_HOME DOCKER_REQUEST_URL --raw

echo "Enabling WordPress debug flags"
cli wp config set WP_DEBUG_DISPLAY true --raw
cli wp config set SCRIPT_DEBUG true --raw

echo "Setting up permalink structure..."
cli wp rewrite structure '/%postname%/' --quiet

echo "Setting up Memcached through object-cache.php..."
cli wp plugin install memcached
cli cp /var/www/html/wp-content/plugins/memcached/object-cache.php /var/www/html/wp-content/object-cache.php
# We only need to copy the object-cache.php file so we can remove the plugin now.
cli wp plugin uninstall memcached

echo "Activating the Simple Staff List plugin..."
cli wp plugin activate simple-staff-list

echo
echo "SUCCESS! You should now be able to access http://localhost:${WP_LISTEN_PORT}/wp-admin/"
echo "You can login by using the username and password both as 'admin'"