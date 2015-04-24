# Laravel5 Simple OAuth2 Server Package

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Very simple package to use OAuth2 in your Laravel5 app. Package is wrapper around [oauth2-php](https://github.com/FriendsOfSymfony/oauth2-php) library.

Current features:
- [Client credentials](https://tools.ietf.org/html/rfc6749#section-1.3.4) grant type
- [Resource Owner Password Credentials](https://tools.ietf.org/html/rfc6749#section-1.3.3)  grant type
- [Refresh token](https://tools.ietf.org/html/rfc6749#section-1.5)
- Middleware for checking valid token & user

TODO:
- [ ] [Authorization Code](https://tools.ietf.org/html/rfc6749#section-1.3.1)  grant type
- [ ] [Implicit](https://tools.ietf.org/html/rfc6749#section-1.3.2) grant type
- [ ] Test
- [ ] Integration with [Travis CI](https://travis-ci.org/)

## Install

Require this package with composer (Packagist) using the following command:

``` bash
$ composer require media24si/simple-oauth2
```

Register the SimpleOAuth2ServiceProvider to the providers array in config/app.php

``` php
'Media24si\SimpleOAuth2\SimpleOAuth2ServiceProvider',
```

Publish vendor files:
``` bash
$ art vendor:publish
```

Migrate your database
``` bash
$ php artisan migrate
```

Create route for generating tokens:
``` php
$ Route::any('/token', '\Media24si\SimpleOAuth2\Http\Controllers\TokenController@token');
```

## USAGE

### Config
When you publish vendor files **simpleoauth2.php** config file will be copied to *config* folder.
Currently only options to configure are:
- username field
- password field
- additional conditions

SimpleOAuth2 uses **Auth::attempt** to authenticate user. 

### Middleware
Package comes with one middleware. To use it, you need to register it `app\Http\Kernel.php` under `$routeMiddleware`
``` php
'oauth' => '\Media24si\SimpleOAuth2\Http\Middleware\SimpleOAuth2'
```

Then in your routes you can request valid oauth2 token:
``` php
Route::get('/protected', ['middleware' => ['oauth'], function() { return 'Protected resource'; }]);
```
Because SimpleOAuth2 is using laravel authentication package you can request authenticated user with oauth:
``` php
Route::get('/protected', ['middleware' => ['oauth', 'auth'], function() { return 'Protected resource with valid user'; }]);
```

### Artisan commands

**oauth2:create-client**

``` bash
$ php artisan oauth2:create-client
```
Create new oauth2 client.

You will need to provide:
- client name
- client redirect uris
- allowed grant types

**oauth2:list-clients**
``` bash
$ php artisan oauth2:list-clients
```
List all clients.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.