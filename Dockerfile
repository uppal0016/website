FROM php:7.4-fpm

# Copy composer.lock and composer.json into the working directory
# COPY composer.lock composer.json /var/www/html/

# Set working directory
WORKDIR /var/www

# Install dependencies for the operating system software
RUN apt-get update 
RUN apt-get install -qq -y build-essential

# command that convert doc, docx to pdf file
RUN apt-get update  && apt-get install -qq -y libreoffice

# RUN apt-get install -qq -y 
RUN apt-get install -qq -y  libpng-dev 
RUN apt-get install -qq -y  libjpeg62-turbo-dev 
RUN apt-get install -qq -y  libfreetype6-dev 
RUN apt-get install -qq -y  locales 
RUN apt-get install -qq -y  zip 
RUN apt-get install -qq -y  jpegoptim optipng pngquant gifsicle 
RUN apt-get install -qq -y  vim 
RUN apt-get install -qq -y  libzip-dev 
RUN apt-get install -qq -y  unzip 
RUN apt-get install -qq -y  git 
RUN apt-get install -qq -y  libonig-dev 
RUN apt-get install -qq -y  curl
RUN yes | apt-get install  imagemagick php7.4-Imagick --fix-missing
RUN apt-get install -y libmagickwand-dev  imagemagick
RUN pecl install imagick
RUN docker-php-ext-enable imagick
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions for php
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install supervisor
RUN apt-get update && apt-get install -y -qq  supervisor

# Copy supervisor configuration file
COPY talent-one.que /etc/supervisor/conf.d/talent_one.conf

# Copy entrypoint script
COPY supervisor.sh /usr/local/bin/supervisor.sh

# Set execute permissions for the entrypoint script
RUN chmod +x /usr/local/bin/supervisor.sh

COPY ./supervisord.conf /etc/supervisor/supervisord.conf
# Set entrypoint to start Supervisor
ENTRYPOINT ["/usr/local/bin/supervisor.sh" , "tail", "-f", "/dev/null"]

COPY talent-one.que /etc/supervisor/conf.d/talent_one.conf

COPY ./custom_php.ini /usr/local/etc/php/conf.d/custom_php.ini

# RUN useradd -G www-data,root -u 1000 -d /home/tlgt tlgt
# RUN mkdir -p /home/tlgt/.composer && \
#     chown -R tlgt:tlgt /home/tlgt
# USER tlgt

