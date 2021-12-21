FROM wordpress:cli-php7.3 AS cli

FROM wordpress:php7.3
RUN apt-get update \
	&& apt-get install --assume-yes --quiet --no-install-recommends gnupg2 subversion mariadb-client less libicu-dev zlib1g-dev \
	&& pecl uninstall xdebug \
	&& pecl -q install xdebug-2.9.8 \
	&& echo 'xdebug.remote_enable=1' >> $PHP_INI_DIR/php.ini \
	&& echo 'xdebug.remote_port=9000' >> $PHP_INI_DIR/php.ini \
	&& echo 'xdebug.remote_host=host.docker.internal' >> $PHP_INI_DIR/php.ini \
	&& echo 'xdebug.remote_autostart=1' >> $PHP_INI_DIR/php.ini \
	&& docker-php-ext-enable xdebug > /dev/null \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && pecl install memcache-4.0.5.2 \
    && docker-php-ext-enable memcache

COPY --from=cli /usr/local/bin/wp /usr/local/bin/wp