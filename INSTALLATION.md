# Installation

First copy `env.example.php` to `env.php`, then use your favourite editor
to make changes in the file.

## Database

You need a database for storage of the reactions. First, please create a
new database and a user with full permissions on that database. Then you
can configure the database settings in the `env.php` file.

If you have MySQL running via the CLI, you can run the following commands:

```sql
CREATE DATABASE `reactions`;
CREATE USER 'reactionsweb'@'localhost' IDENTIFIED BY 'mysecret123';
GRANT ALL on reactions.\* TO 'reactionsweb'@'localhost';
```

## Setup

Once you have your database setup, you can run the `setup.php` script to
create the database:

```bash
php setup.php
```

You should be greeted with an "Setup ok" message.

If you are unable to run the `setup.php` script, you can setup the database
manually by importing the file from `assets/database.sql`.
