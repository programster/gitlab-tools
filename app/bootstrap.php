<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(__DIR__ . '/vendor/autoload.php');

if (!file_exists(__DIR__ . '/.env'))
{
    die("Missing require environment file.");
}

$dotEnv = new \Symfony\Component\Dotenv\Dotenv();
$dotEnv->load(__DIR__ . '/.env');

$requiredVars = array(
    "ENVIRONMENT",
    "GITLAB_URL",
    "GITLAB_ACCESS_TOKEN",
    "MYSQL_HOST",
    "MYSQL_DATABASE",
    "MYSQL_USER",
    "MYSQL_PASSWORD"
);

foreach ($requiredVars as $requiredVar)
{
    $value = getenv($requiredVar);
    
    if ($value === false)
    {
        // environment variable was not set
        throw new Exception("Missing require environment variable: " . $requiredVar);
    }
    
    define($requiredVar, $value);
}

new iRAP\Autoloader\Autoloader([
    __DIR__,
    __DIR__ . '/views',
    __DIR__ . '/models',
    __DIR__ . '/libs',
    __DIR__ . '/controllers'
]);

