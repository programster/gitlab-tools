<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Issue extends GitlabResource
{
    private $m_id;
    private $m_iid;
    private $m_projectId;
    private $m_title;
    private $m_description;
    private $m_state;
    private $m_createdAt;
    private $m_updatedAt;
    private $m_closedAt; //nullable
    private $m_closedBy; // nullable
    private $m_labels; // array
    private $m_milestone;
    private $m_assignees; // array
    private $m_author; // user object
    private $m_assignee; 
    private $m_dueDate;
    private $m_upVotes;
    private $m_downVotes;
    private $m_webUrl;
    private $m_timeStats; // timestats object
    
    
    protected static function getResourceUrl() : string
    {
        return $url = GITLAB_URL . '/api/v4/issues';
    }
    
    
    public static function loadAll(array $data = array()) : array
    {
        $data = [
            'per_page' => 1000,
            'scope' => 'all',
            'state' => 'opened'
        ];
        
        return parent::loadAll($data);
    }
    
    
    public static function loadFromJsonObject(\stdClass $data): GitlabResource
    {
        $issue = new Issue();
        $issue->m_id = $data->id;
        $issue->m_title = $data->title;
        $issue->m_timeStats = TimeStats::createFromJsonObj($data->time_stats);
        $issue->m_projectId = $data->project_id;
        $issue->m_state = $data->state;
        $issue->m_assignee = $data->assignee;
        $issue->m_webUrl = $data->web_url;
        $issue->m_updatedAt = $data->updated_at;
        $issue->m_dueDate = $data->due_date;
        $issue->m_milestone = $data->milestone;
        return $issue;
    }
    
    
    # Accessors
    public function getId() : int { return $this->m_id; }
    public function getTitle() : string { return $this->m_title; }
    public function getDescription() : string { return $this->m_description; }
    public function getTimeStats() : TimeStats { return $this->m_timeStats; }
    public function getProject() : Project { return Project::load($this->m_projectId); }
    public function getState() : string { return $this->m_state; }
    public function getWebUrl() : string { return $this->m_webUrl; }
}