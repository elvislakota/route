<?php

namespace elvislakota\Router\Repositories;

interface NotFoundInterface{

    /**
     * Custom Error 404 Response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function notFound404();

}
