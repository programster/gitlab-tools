<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SiteSpecific
{
    public static function getDb()
    {
        static $db = null;
        
        if ($db === null)
        {
            $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
        }
        
        return $db;
    }
}

