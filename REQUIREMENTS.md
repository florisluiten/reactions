# Requirements

This guide will help you with the requirements for running Reactions. Since
one of the requirements of the Challenge is not using any package, the
actual requirements are very minimal.

1. PHP, PDO and MySQL driver
2. MySQL server

## PHP

You must have PHP installed with PDO and the MySQL PDO driver configured.
Sometimes you have to install PDO manually, see
[php.net](https://www.php.net/manual/en/pdo.installation.php).

On Ubuntu it is as easy as running:

```bash
sudo apt install php php-mysql
```

## MySQL server

You must have a running MySQL (MariaDB) running. This can either be
locally, over the network or over the internet.

Installation on ubuntu:

```bash
sudo apt install mysql-server
```

The application has been tested on 7.2 on a Ubuntu 18.04 machine; but
should run equally fine on any WinOS machine.
