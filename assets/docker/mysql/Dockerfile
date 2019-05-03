FROM mysql:5.6
MAINTAINER Floris Luiten <floris@florisluiten.nl>

ENV MYSQL_ROOT_PASSWORD=verysecret

COPY schema.sql /docker-entrypoint-initdb.d/schema.sql

EXPOSE 3306
