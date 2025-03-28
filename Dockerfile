FROM php:8.4-alpine

WORKDIR /project

ADD bin bin
ADD src src
ADD .php-cost-estimator .php-cost-estimator
ADD composer.* ./

RUN composer install

ENTRYPOINT ["php", "/project/bin/estimate-cost"]