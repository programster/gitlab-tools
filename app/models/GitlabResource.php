<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class GitlabResource
{
    abstract protected static function getResourceUrl() : string; // GITLAB_URL . '/api/v4/issues';
    
    
    public static function loadAll(array $data = array()) : array
    {
        $resources = [];
        $data['private_token'] = GITLAB_ACCESS_TOKEN;
        $data['per_page'] = 1000;
        $data['order_by'] = 'name';
        $data['sort'] = 'asc';
        
        $request = new Programster\GuzzleWrapper\Request(
            Programster\GuzzleWrapper\Method::createGet(), 
            static::getResourceUrl(),
            $data
        );
        
        $response = $request->send();
        $body = $response->getBody();        
        $info = GuzzleHttp\json_decode($body);
        
        if ($info === null)
        {
            throw new Exception("Failed to get issues");
        }
        
        foreach ($info as $issueJsonObj)
        {
            $resources[] = static::loadFromJsonObject($issueJsonObj);
        }
        
        return $resources;
    }
    
    
    public static function load(int $id)
    {
        $url = static::getResourceUrl() . '/' . $id;
                
        $request = new Programster\GuzzleWrapper\Request(
            Programster\GuzzleWrapper\Method::createGet(), 
            $url,
            ['private_token' => GITLAB_ACCESS_TOKEN]
        );
        
        $response = $request->send();
        
        $body = $response->getBody();
        $info = GuzzleHttp\json_decode($body);
        
        if ($info === null)
        {
            throw new Exception("Failed to get resource");
        }
        
        return $resources = static::loadFromJsonObject($info);
    }
    
    
    public abstract static function loadFromJsonObject(stdClass $data) : GitlabResource;    
}