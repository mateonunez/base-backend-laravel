# Base Backend Laravel

![Tests Actions](https://github.com/mateonunez/base-backend-laravel/actions/workflows/tests.yml/badge.svg)

## Setting Test Environment

Copy the `.env.testing` and fill the variables

```shell
cp .env.testing.example .env.testing
```

Generate the application key

```shell
php artisan key:generate --env=testing
```

Run migrations

```shell
php artisan migrate --env=testing
```

Install passport

```shell
php artisan passport:install --uuids --env=testing
```

Run the tests

```shell
php artisan test
```
