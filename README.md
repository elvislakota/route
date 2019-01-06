# Route

[![Author](https://img.shields.io/badge/author-Elvis%20Lakota-blue.svg)](https://instagram.com/lovedevelop)
![](https://img.shields.io/github/issues/elvislakota/route.svg)
![](https://img.shields.io/github/forks/elvislakota/route.svg)
![](https://img.shields.io/github/stars/elvislakota/route.svg)
![](https://img.shields.io/github/license/elvislakota/route.svg)

This package is compliant with [PSR-7]. 
If you notice compliance oversights, please send a patch via pull request.

[PSR-7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md

## Install

Via Composer

``` bash
$ composer require elvislakota/router:dev-master
```


## Requirements

The following versions of PHP are supported by this version.

* PHP 7.1
* PHP 7.2

Usage
-----

Here's a basic usage example:

## .htaccess

Create a .htaccess file in your htdocs folder

````
<ifModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !index
    RewriteRule (.*) public/ [L]

</ifModule>


````


Then create a new Folder 'public' in your htdocs folder 
and add index.php with content


```php
<?php

require '../vendor/autoload.php';

//Your middleware class
$middlewareTest = new \elvislakota\Router\example\MiddlewareTest();

//Custom error messages for the error 404,405 and middleware
$exceptionTest = new \elvislakota\Router\example\ExceptionTest();

//Router
$router = new \elvislakota\Router\Router($exceptionTest);

//Add a route
$router->addRoute(\elvislakota\Router\Router\Route::getRoute('GET','/',
    'elvislakota\Router\example\ControllerTest::helloWorld', $middlewareTest));

//Dispatch and emit data
$serverResponse = $router->dispatch();
$router->emit();

```

## Testing

``` bash
$ vendor/bin/phpunit
```

# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/elvislakota/route).

## Credits

- [Elvis Lakota](https://github.com/elvislakota)
- [Nikita Popov](https://github.com/nikic) (FastRoute)
- [Narrowspark](https://github.com/narrowspark/http-emitter) (http-emitter)
- [All Contributors](https://github.com/elvislakota/route/graphs/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/elvislakota/route/blob/master/LICENSE.md) for more information.
