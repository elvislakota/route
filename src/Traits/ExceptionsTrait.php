<?php

namespace elvislakota\Router\Traits;

use Zend\Diactoros\Response;
use Zend\Diactoros\ResponseFactory;

trait ExceptionsTrait{

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function notAllowed405(){
        $serverRequest = new ResponseFactory();
        return $serverRequest->createResponse(405);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function notFound404(){
        $serverRequest = new ResponseFactory();
        return $serverRequest->createResponse(404);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function middlewareFails(){
        $response = new Response();

        $response->getBody()->write(json_encode(['middleware' => 'failed']));

        return $response;
    }

}
