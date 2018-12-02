<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Project extends GitlabResource
{
    private $m_id;
    private $m_description;
    private $m_name;
    private $nameWithNamespace;
    private $m_path;
    private $m_pathWithNamespace;
    private $m_createdAt;
    private $m_defaultBranch;
    private $m_tagList;
    private $sshUrlToRepo;
    private $m_httpUrlToRepo;
    private $m_webUrl;
    private $m_readmeUrl;
    
    
    public function __construct()
    {
        
    }
    
    
    protected static function getResourceUrl(): string 
    {
        return $url = GITLAB_URL . '/api/v4/projects';
    }

    
    public static function loadFromJsonObject(stdClass $data): GitlabResource 
    {
        $project = new Project();
        $project->m_id = $data->id;
        $project->m_name = $data->name;
        $project->m_description = $data->description;
        return $project;
    }
    
    
    public function loadIssues()
    {
        $issues = array();
        $url = self::getResourceUrl() . '/' . $this->m_id . '/issues' ;
                
        $request = new Programster\GuzzleWrapper\Request(
            Programster\GuzzleWrapper\Method::createGet(), 
            $url,
            ['private_token' => GITLAB_ACCESS_TOKEN, 'per_page' => 1000, 'state' => 'opened']
        );
        
        $response = $request->send();
        $body = $response->getBody();
        $info = GuzzleHttp\json_decode($body);
        
        if ($info === null)
        {
            throw new Exception("Failed to get resource");
        }
        
        foreach ($info as $issue)
        {
            $issues[] = Issue::loadFromJsonObject($issue);
        }
        
        return $issues;
    }
    
    
    # Accessors
    public function getId() { return $this->m_id; }
    public function getName() { return $this->m_name; }
    public function getDescription() { return $this->m_description; }
}
