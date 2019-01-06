<?php

namespace elvislakota\Router;

use elvislakota\Router\Middleware\MiddlewareInterface;
use elvislakota\Router\Router\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase{


    public function call(){
        return 0;
    }

    public function testGetRouteWithoutMiddleware(){


        $route = Route::getRoute('GET','/', 'elvislakota\Router\RouteTest::call');

        $this->assertInstanceOf(Route::class, $route);
        $this->assertArrayHasKey('callable',$route->getCallable());
        $this->assertArrayNotHasKey('middleware',$route->getCallable());

    }

    public function testGetRouteWithMiddleware(){

        $middleware = $this->getMockBuilder(MiddlewareInterface::class)->getMock();

        $route = Route::getRoute('GET','/', 'elvislakota\Router\RouteTest::call', $middleware);

        $this->assertInstanceOf(Route::class, $route);
        $this->assertArrayHasKey('middleware',$route->getCallable());
        $this->assertArrayHasKey('callable',$route->getCallable());

    }
}
