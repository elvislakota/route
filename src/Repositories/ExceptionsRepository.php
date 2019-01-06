<?php

namespace elvislakota\Router\Repositories;

interface ExceptionsRepository extends NotAllowedInterface, NotFoundInterface{

    /**
     * Custom Middleware didn't pass response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function middlewareFails();

}
