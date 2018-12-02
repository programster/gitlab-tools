<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class AbstractController
{
    protected $m_request;
    protected $m_response;
    protected $m_args;


    public function __construct(\Slim\Http\Request $request, Slim\Http\Response $response, $args) {
        $this->m_request = $request;
        $this->m_response = $response;
        $this->m_args = $args;
    }

    // this one is optional - refer to Slim3 - Simplifying Routing At Scale
    // https://blog.programster.org/slim3-simplifying-routing-at-scale
    abstract public static function registerRoutes($app);
}