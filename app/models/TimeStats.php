<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TimeStats
{
    private $m_timeEstimate; // time estimate in seconds (int)
    private $m_totalTimeSpent; // time spent in seconds (int)
    private $m_humanTimeEstimate; // human readable time estimate ("30m")
    private $m_humanTotalTimeSpent; // human readable form of total time spent
    
    
    public static function createFromJsonObj(stdClass $object)
    {
        $statsObj = new TimeStats();
        $statsObj->m_humanTimeEstimate = (string) $object->human_time_estimate;
        $statsObj->m_humanTotalTimeSpent = (string) $object->human_total_time_spent;
        $statsObj->m_timeEstimate = $object->time_estimate;
        $statsObj->m_totalTimeSpent = $object->total_time_spent;
        
        return $statsObj;
    }
    
    
    # Accessors
    public function getTotalTimeSpent() : int { return $this->m_totalTimeSpent ?? 0; }
    public function getTimeEstimate() : int { return $this->m_timeEstimate ?? 0; }
    public function getHumanTimeEstimate() : string { return $this->m_humanTimeEstimate; }
    public function getHumanTotalTimeSpent() : string { return $this->m_humanTotalTimeSpent; }
}