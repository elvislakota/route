<?php

namespace elvislakota\Router\Repositories;

interface NotAllowedInterface{


    /**
     * Custom Error 405 Response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function notAllowed405();

}
