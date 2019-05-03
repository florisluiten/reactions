FROM ubuntu:18.04
MAINTAINER Floris Luiten <floris@florisluiten.nl>

VOLUME ["/var/www"]

COPY assets/docker/settimezone /usr/local/bin/settimezone
RUN chmod +x /usr/local/bin/settimezone
RUN /usr/local/bin/settimezone

RUN apt-get update && apt-get dist-upgrade -y && apt-get install -y \
  apache2 libapache2-mod-php php php-mysql php-sqlite3 mysql-server

COPY assets/docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY assets/docker/apache/ports.conf /etc/apache2/ports.conf
COPY assets/docker/run /usr/local/bin/run

RUN chmod +x /usr/local/bin/run
RUN a2enmod rewrite

EXPOSE 8080
CMD ["/usr/local/bin/run"]
