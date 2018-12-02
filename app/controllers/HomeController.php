<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HomeController extends AbstractController
{
    private function index()
    {
        $navbar = new ViewDefaultNavbar($this->m_request);
        
        $jumbotron = new ViewJumbotron("Gitlab Tools", "Welcome to Gitlab tools!");
        
        $myHtml = new ViewTemplate(
            $tabTitle = "Home", 
            $jumbotron, 
            $navbar, 
            "",
            new ViewFooter()
        );
        
        $newResponse = $this->m_response->getBody()->write($myHtml);
        return $newResponse;
    }
    
    public static function registerRoutes($app) 
    {
        $app->get('/', function  (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
            $homeController = new HomeController($request, $response, $args);
            return $homeController->index();
        });
    }
}

