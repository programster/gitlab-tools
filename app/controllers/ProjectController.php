<?php

/* 
 * 
 */

class ProjectController extends AbstractController
{
    
    /**
     * List all the projects in Gitlab.
     * @return type
     */
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
        
        $filter = array('state' => 'opened');
        $openIssues = $project->loadIssues($filter);
        $allProjectIssues = $project->loadIssues();
        
        $estimate = 0;
        $spent = 0;
        
        foreach ($openIssues as $issue)
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
        
        
        $body = 
            '<div class="row">
                <div class="col-xl-6"><h2>Burndown</h2>' . new ViewBurndownChart(...$allProjectIssues) . '</div>
                <div class="col-xl-6"><h2>Open Issues</h2>' . new ViewProjectIssuesTable(...$openIssues) . '</div>
            </div>';
        
        $myHtml = new ViewTemplate(
            $tabTitle = "Project {$projectId}", 
            $jumbotron, 
            $navbar, 
            $body,
            new ViewFooter()
        );
        
        $newResponse = $this->m_response->getBody()->write($myHtml);
        return $newResponse;
    }
    
    
    /**
     * Register the routes with the Slim app.
     * @param type $app
     */
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

