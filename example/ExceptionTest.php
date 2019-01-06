<?php

namespace elvislakota\Router\example;

use elvislakota\Router\Repositories\ExceptionsRepository;
use elvislakota\Router\Traits\ExceptionsTrait;
use Zend\Diactoros\Response;

class ExceptionTest implements ExceptionsRepository{
    use ExceptionsTrait;

//    /**
//     * If you want to override the method when the middleware fails
//     *
//     * @return \Psr\Http\Message\ResponseInterface
//     */
//    public function middlewareFails(){
//        $response = new Response();
//
//        $response->getBody()->write(json_encode(['middleware' => 'failed']));
//
//        return $response;
//    }

}
