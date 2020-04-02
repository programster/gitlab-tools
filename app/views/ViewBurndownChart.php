<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewBurndownChart extends AbstractView
{
    private $m_progressLogs;
    private $m_chartData;
    
    
    public function __construct(ProgressLog ...$logs)
    {
        $ascSorter = function($a, $b) {
            return $a->getWhen() <=> $b->getWhen();
        };

        uasort($logs, $ascSorter);

        $this->m_progressLogs = $logs;
        $this->m_chartData = array();
        
        if (count($logs) > 0)
        {
            $lastLog = null;

            foreach ($logs as $log)
            {
                /* @var $log ProgressLog */
                $this->m_chartData[] = [$log->getWhen(),  ($log->getSecondsRemaining() / 60 / 60), null];
                $lastLog = $log;
            }

            $lastRow = array_pop($this->m_chartData);
            $lastRow[2] = "Last Record";
            $this->m_chartData[] = $lastRow;

            /* calculate a "work rate" to predict the end assuming no additional tasks added */

            $logs = array_reverse($logs);

            $workRate = 0.3; # assume devs work 8 hours a day which is 8/24 = 0.333...

            if (count($logs) < 3)
            {
                // cannot calculate a work rate, just estimate it as 1 for now.
            }
            else
            {
                $timeEnd = $logs[0]->getWhen();
                $timeStart = $logs[2]->getWhen();
                $timeDiff = $timeEnd - $timeStart;
                $workTimeDone = $logs[0]->getSecondsWorked() + $logs[1]->getSecondsWorked() + $logs[2]->getSecondsWorked();
                $workRate = $workTimeDone / $timeDiff;

                // can't be a burn DOWN if work rate is 0 so set a minimum.
                if ($workRate <= 0)
                {
                    $workRate = 0.1;
                }
            }

            // Using the work rate, add estimated record of when project finishes
            $estimatedTimeNeededToComplete = $lastLog->getSecondsRemaining() / $workRate;
            $estimatedCompletionTimestamp = $lastLog->getWhen() + $estimatedTimeNeededToComplete;
            $this->m_chartData[] = [$estimatedCompletionTimestamp,  0, null];
        }
    }
    
    
    protected function renderContent() 
    {
?>


    <div id="curve_chart" style="width: 100%; height: 500px"></div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {"packages":["corechart"]});
    google.charts.setOnLoadCallback(chartLoadCallback);
    
    /**
     * Hook to be called by google chart when it has finished loading.
     */ 
    function chartLoadCallback()
    {
        drawChart();
        window.onresize = function(event) { drawChart(); };
    }
    
    
    /**
     * Draw the chart. Calling this will replace the chart. You may wish to do this when you
     * resize a window etc.
     */
    function drawChart() 
    {
       var chartData = <?= json_encode($this->m_chartData); ?>
        
        for ( var i = 0; i < chartData.length; i++ ) { 
            chartData[i][0] = new Date( chartData[i][0] * 1000 );
        }
        
        var data = new google.visualization.DataTable();
        data.addColumn("date", "day #");
        data.addColumn("number", "Hours Remaining");
        data.addColumn({type: "string", role: "annotation"});
        
        for (index in chartData)
        {
            let row = chartData[index];
            data.addRow(row);
        }
        
        var options = {
            curveType: "function",
            chartArea: {
                width: "85%", 
                height: "80%"
            },
            selectionMode: "multiple",
            tooltip: { trigger: "both" },
            aggregationTarget: "none",
            focusTarget: "category",
            explorer: {
                axis: "horizontal",
                actions: ["dragToZoom", "rightClickToReset"]
            },
            crosshair: { 
                trigger: "both",
                orientation: "vertical" 
            },
            vAxis: {
                title: "Time Left (hours)",
                gridlines: {
                    color: "transparent"
                }
            },
            hAxis: {
                gridlines: {
                    color: "transparent"
                }
            },
            annotations: {
                style: "dot"
            },
            legend: "none"
        };
        
        var chart = new google.visualization.LineChart(document.getElementById("curve_chart"));
        chart.draw(data, options);
    }
    </script>


<?php
    }
}