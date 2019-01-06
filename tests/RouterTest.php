<?php

namespace elvislakota\Router;

use elvislakota\Router\Middleware\MiddlewareInterface;
use elvislakota\Router\Router\Dispatcher;
use elvislakota\Router\Router\Route;
use elvislakota\Router\test\ExceptionTest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class RouterTest extends TestCase{

    public function call($message = ''){
        $res = new Response();
        $res->getBody()->write($message);
        return $res;

    }

    public function testExctractCallableInfo(){
        $callString = 'elvislakota\Router\RouterTest::call';

        $routeExc = $this->createMock(ExceptionTest::class);

        $dispatcher = new Dispatcher($routeExc);

        $info =$dispatcher->checkMiddleware($routeExc,['middleware' => null,'callable' => $callString],[],'');

        $this->assertInstanceOf(ResponseInterface::class, $info);

    }

    public function testDispatcherError404(){
        $calValue = $this->call('error404');
        $routeExc = $this->createMock(ExceptionTest::class);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $routeExc
            ->method('notFound404')
            ->willReturn($calValue);

        $dispatcher = new Dispatcher($routeExc);
        $dispatched = $dispatcher->dispatch();
        $dispatched->getBody()->rewind();

        $this->assertContains("error404",  $dispatched->getBody()->getContents());

    }

    public function testDispatcherError405(){
        $calValue = $this->call('error405');
        $routeExc = $this->createMock(ExceptionTest::class);
        $callString = 'elvislakota\Router\RouterTest::call';

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/';

        $routeExc
            ->method('notAllowed405')
            ->willReturn($calValue);

        $dispatcher = new Dispatcher($routeExc);


        $dispatcher->getRouteCollector()->addRoute('GET', '/', $callString);


        $dispatched = $dispatcher->dispatch();
        $dispatched->getBody()->rewind();

        $this->assertContains("error405",  $dispatched->getBody()->getContents());

    }

    public function testHandleMiddlewareFails(){
        $calValue = $this->call('middlewareFailed');
        $routeExc = $this->createMock(ExceptionTest::class);
        $middlewareClass = $this->createMock(MiddlewareInterface::class);
        $callString = 'elvislakota\Router\RouterTest::call';

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/';

        $routeExc
            ->method('middlewareFails')
            ->willReturn($calValue);


        $middlewareClass
            ->method('next')
            ->willReturn(false);


        $router = new Router($routeExc);




        $router->addRoute(Route::getRoute(
            'POST',
            '/',
            $callString,$middlewareClass
        ));


        $dispatched = $router->dispatch();
        $dispatched->getBody()->rewind();

        $this->assertContains("middlewareFailed",
            $dispatched->getBody()->getContents());

    }

    public function testHandleMiddlewareSuccesses(){
        $calValue = $this->call('middlewareFailed');
        $routeExc = $this->createMock(ExceptionTest::class);
        $middlewareClass = $this->createMock(MiddlewareInterface::class);
        $callString = 'elvislakota\Router\RouterTest::call';

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/?foo=bar';

        $routeExc
            ->method('middlewareFails')
            ->willReturn($calValue);


        $middlewareClass
            ->method('next')
            ->willReturn(true);


        $router = new Router($routeExc);




        $router->addRoute(Route::getRoute(
            'POST',
            '/',
            $callString,$middlewareClass
        ));


        $dispatched = $router->dispatch();
        $dispatched->getBody()->rewind();

        $this->assertContains("",$dispatched->getBody()->getContents());

    }
}
