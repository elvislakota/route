<?php

namespace elvislakota\Router\Middleware;

interface MiddlewareInterface{

    /**
     * If its true The app continues, otherwise an error will be triggered
     *
     * @return bool
     */
    public function next();

}
