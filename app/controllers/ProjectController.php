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
    private function viewProject(int $projectId)
    {
        /* @var $project Project */
        $project = Project::load($projectId);
        $navbar = new ViewDefaultNavbar($this->m_request);
        
        $openIssues = array();
        $allProjectIssues = $project->loadIssues();
        
        $estimate = 0;
        $spent = 0;
        
        foreach ($allProjectIssues as $issue)
        {
            /* @var $issue Issue */
            if ($issue->getState() !== "closed")
            {
                $openIssues[] = $issue;
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
        
        $logs = ProgressLog::loadForProject($project);
        
        $body = 
            '<div class="row">
                <div class="col-xl-6"><h2>Burndown</h2>' . new ViewBurndownChart(...$logs) . '</div>
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
     * Export a CSV of this project's issues. 
     * Useful for poeple who want to manipulate the data in excel.
     * @param int $projectId - the ID of the project to export.
     * @return type
     */
    public function export(int $projectId)
    {
        /* @var $project Project */
        $project = Project::load($projectId);
        
        $navbar = new ViewDefaultNavbar($this->m_request);
        
        $allProjectIssues = $project->loadIssues();
        
        $estimate = 0;
        $spent = 0;
        
        $exportData = array();
        $allLabels = array();
        
        foreach ($allProjectIssues as $issue)
        {
            foreach ($issue->getLabels() as $label)
            {
                $allLabels[$label] = 1;
            }
        }
        
        $allLabels = array_keys($allLabels);
                
        foreach ($allProjectIssues as $issue)
        {
            /* @var $issue Issue */
            $issueArray = array(
                "Issue ID" => $issue->getId(),
                "Title" => $issue->getTitle(),
                "State" => $issue->getState(),
                "Time Spent (seconds)" => $issue->getTimeStats()->getTotalTimeSpent(),
                "Time Estimate (seconds)" => $issue->getTimeStats()->getTimeEstimate(),
                "Time Spent (human)" => $issue->getTimeStats()->getHumanTotalTimeSpent(),
                "Time Estimate (human)" => $issue->getTimeStats()->getHumanTimeEstimate(),
                "Assignee" => $issue->getAssignee(),
            );
            
            foreach ($issue->getLabels() as $issueLabel)
            {
                $issueArray[$issueLabel] = 1;
            }
            
            foreach ($allLabels as $label)
            {
                if (!isset($issueArray[$label]))
                {
                    $issueArray[$label] = 0;
                }
            }
            
            $exportData[] = $issueArray;
        }
        
        $tempFilename = tempnam('/tmp', 'export_');
        iRAP\CoreLibs\CsvLib::convertArrayToCsv($tempFilename, $exportData, true);
        
        // Output as CSV
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        fwrite($tempFileHandle, BYTE_ORDER_MARK); // BOM first for excel to work
        $outputStream = fopen("php://output", 'w');
        $content = file_get_contents($tempFilename);
        fwrite($outputStream, $content);
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
        
        $app->get('/projects/{id:[0-9]+}/export', function  (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
            $controller = new ProjectController($request, $response, $args);
            return $controller->export($args['id']);
        });
    }
}

