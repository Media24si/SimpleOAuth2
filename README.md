# THIS PROJECT IS DEPRECATED. Use [Laravel Passport] (https://laravel.com/docs/master/passport) instead

# Laravel5 Simple OAuth2 Server Package

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

A very simple OAuth2 package to use in your Laravel5 app. The package is a wrapper around the [oauth2-php](https://github.com/FriendsOfSymfony/oauth2-php) library and is inspired by [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle).

Current features:
- [Client credentials](https://tools.ietf.org/html/rfc6749#section-1.3.4) grant type
- [Resource Owner Password Credentials](https://tools.ietf.org/html/rfc6749#section-1.3.3)  grant type
- [Refresh token](https://tools.ietf.org/html/rfc6749#section-1.5)
- [Grant extensions](https://tools.ietf.org/html/rfc6749#section-4.5)
- Middleware for checking valid token & user

TODO:
- [ ] Scopes
- [x] [Authorization Code](https://tools.ietf.org/html/rfc6749#section-1.3.1)  grant type
- [x] [Implicit](https://tools.ietf.org/html/rfc6749#section-1.3.2) grant type
- [ ] Test
- [ ] Integration with [Travis CI](https://travis-ci.org/)

## Install

Require this package with composer (Packagist) using the following command:

``` bash
$ composer require media24si/simple-oauth2
```

Register the SimpleOAuth2ServiceProvider to the providers array in config/app.php

``` php
Media24si\SimpleOAuth2\SimpleOAuth2ServiceProvider::class,
```

Publish vendor files:
``` bash
$ php artisan vendor:publish
```

Migrate your database
``` bash
$ php artisan migrate
```

Create a route for generating tokens:
``` php
$ Route::any('/token', '\Media24si\SimpleOAuth2\Http\Controllers\TokenController@token');
```

If you want to use Authorization code or Implicit grant type add authore controller to rote:
``` php
Route::any('/authorize', ['middleware' => 'auth','uses' => '\Media24si\SimpleOAuth2\Http\Controllers\AuthorizeController@authorize']);
```

## USAGE

### Config
When you publish vendor files, the **simpleoauth2.php** config file will be copied to the *config* folder.
Currently the only configuration options are:
- a username field
- a password field
- additional conditions

SimpleOAuth2 uses **Auth::attempt** to authenticate users.

### Middleware
The package comes with one middleware. To use it, you need to register `app\Http\Kernel.php` under `$routeMiddleware`
``` php
'oauth' => '\Media24si\SimpleOAuth2\Http\Middleware\SimpleOAuth2'
```

Then in your routes you can request a valid oauth2 token:
``` php
Route::get('/protected', ['middleware' => ['oauth'], function() { return 'Protected resource'; }]);
```
Because SimpleOAuth2 is using Laravel authentication package you can request an authenticated user with oauth:
``` php
Route::get('/protected', ['middleware' => ['oauth', 'auth'], function() { return 'Protected resource with valid user'; }]);
```

### Artisan commands

**oauth2:create-client**

``` bash
$ php artisan oauth2:create-client {client_name}
```
Create a new oauth2 client. For all options see help

``` bash
$ php artisan help oauth2:create-client {client_name}
```

**oauth2:list-clients**
``` bash
$ php artisan oauth2:list-clients
```
List all oauth2 clients.

## Grant types

### Authorization Code and Implicit

To use authorization code or implicit grant types you have to provide auth middleware so user can login.
You can customize authorize by implementing your own action and view.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
