<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(__DIR__ . '/settings.php');
require_once(__DIR__ . '/vendor/autoload.php');


new iRAP\Autoloader\Autoloader([
    __DIR__,
    __DIR__ . '/views',
    __DIR__ . '/models',
    __DIR__ . '/libs',
    __DIR__ . '/controllers'
]);

