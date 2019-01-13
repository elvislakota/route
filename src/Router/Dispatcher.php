<?php

namespace elvislakota\Router\Router;

use FastRoute\DataGenerator;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;
use FastRoute\Dispatcher as FastDispatcher;
use elvislakota\Router\Emitter;
use elvislakota\Router\Middleware\MiddlewareInterface;
use elvislakota\Router\Repositories\ExceptionsRepository;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Dispatcher
 * @package elvislakota\Router\Router
 */
class Dispatcher extends Emitter{

    /**
     * @var $routeParser RouteParser
     */
    protected $routeParser;

    /**
     * @var $dataGenerator DataGenerator
     */
    protected $dataGenerator;

    /**
     * @var $dispatcher \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var $routeCollector RouteCollector
     */
    protected $routeCollector;

    /**
     * @var $routeExceptions ExceptionsRepository
     */
    protected $routeExceptions;

    /**
     * Router constructor.
     *
     * @param ExceptionsRepository $routeExceptions
     */
    public function __construct(ExceptionsRepository $routeExceptions){
        $this->routeExceptions = $routeExceptions;
        $this->initDefault();

    }

    public function initDefault(){
        $this->routeParser = new RouteParser\Std();
        $this->dataGenerator = new DataGenerator\GroupCountBased();

        $this->routeCollector = new RouteCollector($this->routeParser,$this->dataGenerator);

    }

    /**
     * @return RouteCollector
     */
    public function getRouteCollector(): RouteCollector{
        return $this->routeCollector;
    }

    /**
     * @return \FastRoute\Dispatcher
     */
    public function getDispatcher(): FastDispatcher{
        if ($this->dispatcher instanceof FastDispatcher){
            return $this->dispatcher;
        }
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dispatch(){
        $routeInfo = $this->dispatchRouteInfo();

        $this->initSapiEmitter();
        return $this->parseRequest($this->routeExceptions, $routeInfo);
    }

    /**
     * @return bool|string
     */
    protected function getUri(){
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        return $uri;
    }


    /**
     * @param ExceptionsRepository $exceptionHandlers
     * @param                      $routeInfo
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function parseRequest(ExceptionsRepository $exceptionHandlers, $routeInfo){
        switch ($routeInfo[0]) {
            case FastDispatcher::NOT_FOUND:
                return $this->triggerError404($exceptionHandlers);
                break;
            case FastDispatcher::METHOD_NOT_ALLOWED:
                return $this->triggerError405($exceptionHandlers);
                break;
            case FastDispatcher::FOUND:
                $callable = $routeInfo[1];
                $vars = $routeInfo[2];
                $outputSuccess = $this->checkMiddleware($exceptionHandlers, $callable, $vars);
                return $outputSuccess;
                break;
        }
    }

    protected function createDispatcher(): void{
        $this->dispatcher = new GroupCountBased($this->routeCollector->getData());
    }

    /**
     * @param ExceptionsRepository $exceptionHandlers
     * @param                      $callable
     * @param                      $vars
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function checkMiddleware(ExceptionsRepository $exceptionHandlers, $callable, $vars){
        if ($callable['middleware'] instanceof MiddlewareInterface) {
            $outputSuccess = $this->handleMiddleware($exceptionHandlers, $callable, $vars);
        } else {
            $outputSuccess = $this->callUserFunc($callable, $vars);
        }
        return $outputSuccess;
    }

    /**
     * @param ExceptionsRepository $exceptionHandlers
     * @param                      $callable
     * @param                      $vars
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function handleMiddleware(ExceptionsRepository $exceptionHandlers, $callable, $vars){
        /** @var $middleware MiddlewareInterface */
        $middleware = $callable['middleware'];
        if ($middleware->next() === true) {
            $outputSuccess = $this->callUserFunc($callable, $vars);
        } else {
            $outputSuccess = $exceptionHandlers->middlewareFails();
            $this->setServerResponse($outputSuccess);
        }
        return $outputSuccess;
    }

    /**
     * @param ExceptionsRepository $exceptionHandlers
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function triggerError404(ExceptionsRepository $exceptionHandlers): ResponseInterface{
        $output404 = $exceptionHandlers->notFound404();
        $this->setServerResponse($output404);
        return $output404;
    }

    /**
     * @param ExceptionsRepository $exceptionHandlers
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function triggerError405(ExceptionsRepository $exceptionHandlers): ResponseInterface{
        $output405 = $exceptionHandlers->notAllowed405();
        $this->setServerResponse($output405);
        return $output405;
    }

    /**
     * @param $callable callable
     * @param $vars array
     *
     * @return ResponseInterface
     */
    private function callUserFunc($callable, $vars): ResponseInterface{
        $callableInfo = $this->extractCallableInfo($callable['callable']);
        $Controller = new $callableInfo['class'];

        $outputSuccess = call_user_func([$Controller, $callableInfo['method']], $vars);
        $this->setServerResponse($outputSuccess);
        return $outputSuccess;
    }

    /**
     * @param $callableString callable
     *
     * @return array
     */
    private function extractCallableInfo(callable $callableString): array{
        $functionInfo = explode('::', $callableString);
        return ['class' => $functionInfo[0], 'method' => $functionInfo[1]];
    }

    /**
     * @return array
     */
    private function dispatchRouteInfo(): array{
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();
        $this->createDispatcher();

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        return $routeInfo;
    }

}
