# Reactions

This repository contain a solution to the Reactions challenge. It is meant
as a demonstration, and is not (yet) ready for use in production.

In order to run this application, checkout
[REQUIREMENTS.md](REQUIREMENTS.md) first. That guide will help you setup
your machine for running this application.

If you have your requirements set, you should continue with reading
[INSTALLATION.md](INSTALLATION.md). This will setup your environment so you
can actually use the application.

With the requirements set, and installation done you can finally start the
application. You may use the PHP Built-in webserver like so:

```bash
php -S localhost:8080 -t public/
```

Then browse to http://localhost:8080 and use the application.

## Testing

In order to test the codebase, you must have [phpunit](https://phpunit.de)
installed. Then you can run `phpunit` from the root of this repository.
