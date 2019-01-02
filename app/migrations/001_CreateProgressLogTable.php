<?php

/* 
 * Creates the progress_log table which will keep track of how much work has been done
 * over time.
 */

class CreateProgressLogTable implements iRAP\Migrations\MigrationInterface
{
    public function up(\mysqli $mysqliConn) 
    {
        $query = 
            "CREATE TABLE `progress_log` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `project_id` int unsigned NOT NULL,
                `seconds_remaining` int unsigned NOT NULL,
                `seconds_worked` int unsigned NOT NULL,
                `open_issues` int unsigned NOT NULL,
                `closed_issues` int unsigned NOT NULL,
                `when` int unsigned,
                PRIMARY KEY (`id`),
                INDEX (`project_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        $result = $mysqliConn->query($query);
        
        if ($result === FALSE)
        {
            print $mysqliConn->error . PHP_EOL;
            die("Failed to create the progress_log table");
        }
    }
    
    
    public function down(\mysqli $mysqliConn) 
    {
        $query = "DROP TABLE `progress_log`";
        $result = $mysqliConn->query($query);
        
        if ($result === FALSE)
        {
            print $mysqliConn->error . PHP_EOL;
            die("Failed to drop the progress_log table");
        }
    }

}

