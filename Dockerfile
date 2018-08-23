FROM composer:latest

RUN git clone https://github.com/uzulla/yet-another-bulk-mailer
WORKDIR yet-another-bulk-mailer
RUN composer install
EXPOSE 8080
ENTRYPOINT ["php", "-S", "0.0.0.0:8080"]
