<?php

/* 
 * 
 */

class ProjectController extends AbstractController
{
    private function index()
    {
        $projects = Project::loadAll();
        $navbar = new ViewDefaultNavbar($this->m_request);
        $jumbotron = new ViewJumbotron("Projects", "View your projects.");
        
        $myHtml = new ViewTemplate(
            $tabTitle = "Home", 
            $jumbotron, 
            $navbar, 
            new ViewProjectsTable(...$projects),
            new ViewFooter()
        );
        
        $newResponse = $this->m_response->getBody()->write($myHtml);
        return $newResponse;
    }
    
    
    /**
     * Display the view for a single project.
     * @param int $projectId
     * @return type
     */
    public function viewProject(int $projectId)
    {
        /* @var $project Project */
        $project = Project::load($projectId);
        $navbar = new ViewDefaultNavbar($this->m_request);
        
        $projectIssues = $project->loadIssues();
        
        $estimate = 0;
        $spent = 0;
        
        foreach ($projectIssues as $issue)
        {
            /* @var $issue Issue */
            if ($issue->getState() !== "closed")
            {
                $timeStats = $issue->getTimeStats();
                
                if ($timeStats->getTotalTimeSpent() < $timeStats->getTimeEstimate())
                {
                    $spent += $timeStats->getTotalTimeSpent();
                    $estimate += $timeStats->getTimeEstimate();
                }
            }
        }
        
        $spent = number_format($spent / (60 * 60), 2);
        $estimate = number_format($estimate / (60*60), 2);
        
        
        $jumbotron = new ViewJumbotron(
            $project->getName(), 
            (string) $project->getDescription() . "</p><p>{$spent} / {$estimate} hours"
        );
        
        
        $myHtml = new ViewTemplate(
            $tabTitle = "Project {$projectId}", 
            $jumbotron, 
            $navbar, 
            new ViewIssuesTable(...$projectIssues),
            new ViewFooter()
        );
        
        $newResponse = $this->m_response->getBody()->write($myHtml);
        return $newResponse;
    }
    
    
    public static function registerRoutes($app) 
    {
        $app->get('/projects', function  (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
            $controller = new ProjectController($request, $response, $args);
            return $controller->index();
        });
        
        $app->get('/projects/{id:[0-9]+}', function  (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
            $controller = new ProjectController($request, $response, $args);
            return $controller->viewProject($args['id']);
        });
    }
}

