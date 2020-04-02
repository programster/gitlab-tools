<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProgressLog
{
    private $id;
    private $project_id;
    private $seconds_remaining;
    private $seconds_worked;
    private $open_issues;
    private $closed_issues;
    private $when;
    
    
    private function __construct()
    {
        
    }
    
    
    /**
     * Load all the progress logs for a project. Useful for generating reports/burndown.
     * @param Project $project
     * @return array
     * @throws Exception
     */
    public static function loadForProject(Project $project) : array
    {
        $logs = array();
        $db = SiteSpecific::getDb();
        $query = "SELECT * FROM `progress_log` where `project_id`={$project->getId()} ORDER BY `when` ASC";
        $result = $db->query($query);
        
        if ($result === FALSE)
        {
            throw new Exception("Failed to grab progress logs for project");
        }
        
        /* @var $result mysqli_result */
        
        if ($result->num_rows > 0)
        {
            while (($object = $result->fetch_object(__CLASS__)) != null)
            {
                $logs[] = $object;
            }

            if (count($logs) == 0)
            {
                die($query);
            }
        }
        else 
        {
            $logs = array();
        }
        
        return $logs;
    }
    
    
    # Accessors
    public function getWhen() : int { return $this->when; }
    public function getSecondsRemaining() : int { return $this->seconds_remaining; }
    public function getSecondsWorked() : int { return $this->seconds_worked; }
    public function getNumberOfIssuesOpen() : int { return $this->open_issues; }
    public function getNumberOfIssuesClosed() : int { return $this->closed_issues; }
}
