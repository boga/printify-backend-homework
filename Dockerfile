FROM php:7.4-fpm-alpine
LABEL maintainer="Ivan Shnurchenko <ivan.shnurchenko@gmail.com>"

RUN apk update \
 && apk add libmcrypt-dev zlib zlib-dev coreutils autoconf gcc libc-dev make \
            mc htop ncdu tmux nano mariadb-client fish fish-tools \
            libzip libzip-dev \
            oniguruma oniguruma-dev

RUN pecl install xdebug mcrypt

RUN docker-php-ext-install mbstring exif opcache pdo pdo_mysql zip
RUN docker-php-ext-enable xdebug
RUN mkdir -p /logs/xdebug/profiler
RUN echo -e "\n\
    xdebug.default_enable = 1 \n\
    xdebug.remote_enable = 1 \n\
    xdebug.remote_autostart = 1  \n\
    xdebug.remote_connect_back = 0 \n\
    xdebug.remote_host = host.docker.internal \n\
    xdebug.remote_port = 9001 \n\
    xdebug.profiler_enable = 0  \n\
    xdebug.profiler_output_dir = /xdebug/profiler \n\
    xdebug.profiler_output_name = cachegrind.out.%u.%p \n\
    xdebug.remote_log = /logs/xdebug/xdebug.log \n\
    " >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#RUN cat /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
#RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'baf1608c33254d00611ac1705c1d9958c817a1a33bce370c0595974b342601bd80b92a3f46067da89e3b06bff421f182') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin
RUN ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
RUN php -r "unlink('composer-setup.php');"

#COPY conf/ /usr/local/etc/php-fpm.d/
RUN echo -e "\n\
[www] \n\
pm.max_children = 50 \n\
" >> /usr/local/etc/php-fpm.d/fpm.conf

# For composer installed binaries
ENV PATH="/root/.composer/vendor/bin:/root/.config/composer/vendor/bin:$PATH"
EXPOSE 9000

ARG PUID=1000
ARG PGID=1000

RUN addgroup -g ${PGID} phpuser && \
    adduser -D -u ${PUID} -G phpuser phpuser

USER phpuser

CMD ["php-fpm"]
