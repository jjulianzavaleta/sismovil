FROM php:7.3.28-apache

ENV ACCEPT_EULA=Y

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install apt-utils libxml2-dev gnupg apt-transport-https \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install MS ODBC Driver for SQL Server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/9/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && apt-get -y --no-install-recommends install msodbcsql17 unixodbc-dev

# Enable sqlsrv library
RUN docker-php-ext-install mbstring pdo pdo_mysql \
    && pecl install sqlsrv-5.9.0 pdo_sqlsrv-5.9.0 xdebug-3.1.5 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv xdebug

# Enable required extensions
RUN docker-php-ext-install intl mysqli

# Install and enable PostgreSQL and ldap
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apt-get install libldap2-dev -y \
    && docker-php-ext-configure ldap \
    && docker-php-ext-install ldap \
    && apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ libssl-dev apt-transport-https gnupg

# Install and enable zip
RUN apt-get update \
    && apt-get install -y libzip-dev \
    && docker-php-ext-configure zip --with-zlib-dir=/usr \
    && docker-php-ext-install zip

# Downgrade OpenSSL to connect to SQLServer 2018
RUN apt-get update -yqq \
    && apt-get install -y --no-install-recommends openssl \
    && sed -i 's,^\(MinProtocol[ ]*=\).*,\1'TLSv1.0',g' /etc/ssl/openssl.cnf \
    && sed -i 's,^\(CipherString[ ]*=\).*,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf\
    && rm -rf /var/lib/apt/lists/*

# Add custom limits for uploading files
RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini
         
# Enable error logging
RUN echo "display_startup_errors = On\n" \
         "display_errors = On\n" \
         "error_reporting = E_ALL\n" \
         > /usr/local/etc/php/conf.d/errorlogging.ini

# Instaling php-gd
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd