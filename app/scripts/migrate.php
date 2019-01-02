<?php

/* 
 * Runs migrations
 */

require_once(__DIR__ . '/../bootstrap.php');
$migrationManager = new iRAP\Migrations\MigrationManager(__DIR__ . '/../migrations', SiteSpecific::getDb());
$migrationManager->migrate();