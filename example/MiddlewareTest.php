<?php

namespace elvislakota\Router\example;

use elvislakota\Router\Middleware\MiddlewareInterface;

class MiddlewareTest implements MiddlewareInterface{

    /**
     * @return bool
     */
    public function next(){

        return true;
    }


}
