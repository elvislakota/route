<?php

namespace elvislakota\Router;

use Narrowspark\HttpEmitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;

class Emitter{


    /**
     * @var $httpEmitter SapiEmitter
     */
    protected $httpEmitter;


    /**
     * @var $serverResponse ResponseInterface
     */
    protected $serverResponse;

    /**
     * Emits the PSR-7 Response
     */
    public function emit(){
        if ($this->httpEmitter instanceof SapiEmitter){
            $this->httpEmitter->emit($this->serverResponse);
        }

        //TODO: Add Exceptions
    }

    /**
     * @param ResponseInterface $serverResponse
     */
    public function setServerResponse(ResponseInterface $serverResponse): void{
        $this->serverResponse = $serverResponse;
    }

    /**
     * Inits the SapiEmitter
     */
    protected function initSapiEmitter(): void{
        $this->httpEmitter = new SapiEmitter();
    }


}