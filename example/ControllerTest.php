<?php

namespace elvislakota\Router\example;

use Zend\Diactoros\Response;

class ControllerTest{

    public function helloWorld(){
        $res = new Response();

        $ip_address_json = $this->getIp();

        $json_beautified = str_replace(
            array("{", "}", '","'),
            array("{<br />&nbsp;&nbsp;&nbsp;&nbsp;", "<br />}",
                '",<br />&nbsp;&nbsp;&nbsp;&nbsp;"'),
            $ip_address_json);

        $json_beautified.= '<br /><br />Thank you for Using elvislakota\Router Package';


        $res->getBody()->write($json_beautified);
        return $res;
    }

    protected function getIp(){
        $ip_address = file_get_contents('https://api.ipify.org/?format=json');
        $ip_address_object = json_decode($ip_address, true);

        $ip_address_info = file_get_contents('http://ip-api.com/json/' . $ip_address_object['ip']);

        return $ip_address_info;

    }


}
