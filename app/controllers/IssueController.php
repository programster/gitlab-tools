<?php

/* 
 * 
 */

class IssueController extends AbstractController
{
    private function index()
    {
        $issues = Issue::loadAll();
        //die(print_r($issues, true));
        $navbar = new ViewDefaultNavbar($this->m_request);
        $jumbotron = new ViewJumbotron("Issues", "View your project issues. There's nothing I can do about your personal ones.");
        
        $myHtml = new ViewTemplate(
            $tabTitle = "Home", 
            $jumbotron, 
            $navbar, 
            new ViewIssuesTable(...$issues),
            new ViewFooter()
        );
        
        $newResponse = $this->m_response->getBody()->write($myHtml);
        return $newResponse;
    }
    
    public static function registerRoutes($app) 
    {
        $app->get('/issues', function  (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
            $issueController = new IssueController($request, $response, $args);
            return $issueController->index();
        });
    }
}

