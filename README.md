# Base Backend

![Tests Actions](https://github.com/sabicom/base-backend/actions/workflows/tests.yml/badge.svg)

## Setting Test Environment

At first clone the `.env.testing.example` into `.env.testing`. Fill the empty variables. And create a new app key with the following command.

```shell
php artisan key:generate --env=testing
```

Run the tests:

```shell
php artisan test
```
