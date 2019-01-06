<?php

require '../vendor/autoload.php';

$middlewareTest = new \elvislakota\Router\example\MiddlewareTest();
$exceptionTest = new \elvislakota\Router\example\ExceptionTest();

$router = new \elvislakota\Router\Router($exceptionTest);

$router->addRoute(\elvislakota\Router\Router\Route::getRoute('GET','/',
    'elvislakota\Router\example\ControllerTest::helloWorld', $middlewareTest));




$router->dispatch();
$router->emit();
