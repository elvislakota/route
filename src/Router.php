<?php

namespace elvislakota\Router;

use elvislakota\Router\Router\Dispatcher;
use elvislakota\Router\Router\Route;

class Router extends Dispatcher{

    /**
     * @param $route Route
     */
    public function addRoute(Route $route){
        $this->getRouteCollector()->addRoute($route->getMethod(), $route->getUri(),$route->getCallable());
    }

    /**
     * @param $routes Route[]
     */
    public function addRoutes($routes){
        foreach ($routes as $route) {
            if ($route instanceof Route){
                $this->addRoute($route);
            }
        }
    }

}

