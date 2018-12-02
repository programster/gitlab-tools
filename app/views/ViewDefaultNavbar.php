<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewDefaultNavbar extends AbstractView
{
    private $m_path;
    
    
    public function __construct(\Slim\Http\Request $request)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        $this->m_path = $path;
    }
    
    
    protected function renderContent() 
    {
        $links = [
            new ViewNavLink("Home", "/", ($this->m_path === "/") ? true : false),
            new ViewNavLink("Projects", "/projects", ($this->m_path === "/projects") ? true : false),
            new ViewNavLink("Issues", "/issues", ($this->m_path === "/issues") ? true : false),
        ];
        
        $navbar = new ViewNavbar(...$links);
        print $navbar;
    }

}