FROM rafitech/php-ubuntu:8.4-fpm-ubuntu24.04-composer-nginx

# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update && apt-get install -y nodejs && npm install -g npm@11.6.0

WORKDIR /var/www/html

COPY ./codes .

# Test it
RUN which laravel && laravel --version

COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
CMD ["/entrypoint.sh"]
