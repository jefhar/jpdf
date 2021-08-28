# jefhar/jpdf
# For unit testing and deployment
# Set the base image for subsequent instructions
FROM phpdockerio/php74-cli:latest
ARG BUILD_DATE
ARG VCS_REF

LABEL maintainer="Jeff Harris <jeff@jeffharris.us>" \
org.label-schema.build-date=$BUILD_DATE \
org.label-schema.description="Fork of MPDS." \
org.label-schema.name="main.jpdf" \
org.label-schema.schema-version="1.0" \
org.label-schema.url="https://jpdf.jeffharris.us" \
org.label-schema.vcs-ref=$VCS_REF \
org.label-schema.vcs-url="https://github.com/jefhar/jpdf" \
PHP="7.4"

# Update packages
RUN apt-get update \
    && apt-get -y remove php-apcu \
        php7.4-zip \
    && apt-get -y --no-install-recommends install \
        php7.4-bcmath \
        php7.4-curl \
        php7.4-gd \
        php7.4-mbstring \
        php7.4-xml \
        make \
    && apt-get install -y --only-upgrade php7.4-cli php7.4-common \
    && apt-get autoremove -y \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Update composer
RUN  composer self-update
