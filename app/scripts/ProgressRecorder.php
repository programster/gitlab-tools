<?php

/* 
 * This script will loop through the projects in gitlab and record their progress to the log table.
 */


require_once(__DIR__ . '/../bootstrap.php');

function main()
{
    $projects = Project::loadAll();
    $insertRows = array();
    
    foreach ($projects as $project)
    {
        /* @var $project Project */
        $issues = $project->loadIssues();
        $secondsRemaining = 0;
        $secondsWorked = 0;
        $issuesOpen = 0;
        $issuesClosed = 0;
        
        foreach ($issues as $issue)
        {
            $timeStats = $issue->getTimeStats();
            
            /* @var $issue Issue */
            if ($issue->getState() !== "closed")
            {
                $issuesOpen++;
                $secondsWorked += $timeStats->getTotalTimeSpent();
                
                if ($timeStats->getTotalTimeSpent() < $timeStats->getTimeEstimate())
                {
                    $issueSecondsRemaining = $timeStats->getTimeEstimate() - $timeStats->getTotalTimeSpent();
                    $secondsRemaining += $issueSecondsRemaining;
                }
            }
            else
            {
                $issuesClosed++;
                
                if (!empty($timeStats->getTotalTimeSpent()))
                {
                    $secondsWorked += $timeStats->getTotalTimeSpent();
                }
                else
                {
                    // developer didnt say how much time was spent, so use the estimate.
                    $secondsWorked += $timeStats->getTimeEstimate();
                }
            }
        }
        
        $insertRows[] = array(
            'project_id' => $project->getId(),
            'seconds_remaining' => $secondsRemaining,
            'seconds_worked' => $secondsWorked,
            'open_issues' => $issuesOpen,
            'closed_issues' => $issuesClosed,
            'when' => time(),
        );
    }
    
    $db = SiteSpecific::getDb();
    $query = iRAP\CoreLibs\MysqliLib::generateBatchInsertQuery($insertRows, "progress_log", $db);
    $result = $db->query($query);
    
    if ($result === FALSE)
    {
        throw new Exception("Failed to insert progress logs.");
    }
}


main();