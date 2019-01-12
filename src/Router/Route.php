<?php

namespace elvislakota\Router\Router;
use elvislakota\Router\Middleware\MiddlewareInterface;

/**
 * Class Route
 * @package elvislakota\Router\Router
 */
class Route{

    /**
     * @var $method string
     */
    protected $method;

    /**
     * @var $uri string
     */
    protected $uri;

    /**
     * @var $callable callable
     */
    protected $callable;

    /**
     * Route constructor.
     *
     * @param string              $method
     * @param string              $uri
     * @param callable            $callable
     * @param MiddlewareInterface $middleware
     */
    public function __construct(string $method, string $uri, callable $callable, MiddlewareInterface $middleware = null){
        $this->method = $method;
        $this->uri = $uri;
        $this->callable = ['callable' => $callable, 'middleware' => $middleware];
      
    }


    /**
     * @param string              $method
     * @param string              $uri
     * @param callable            $callable
     * @param MiddlewareInterface $middleware
     *
     * @return Route
     */
    public static function getRoute(string $method, string $uri, callable $callable, MiddlewareInterface $middleware = null){
        return new static($method, $uri, $callable, $middleware);
    }

    /**
     * @return string
     */
    public function getMethod(): string{
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string{
        return $this->uri;
    }

    /**
     * @return callable
     */
    public function getCallable(){
        return $this->callable;
    }



}

