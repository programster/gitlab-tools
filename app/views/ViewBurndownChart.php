<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewBurndownChart extends AbstractView
{
    private $m_issues;
    
    
    public function __construct(Issue ...$issues)
    {
        $this->m_issues = $issues;
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
        var chartData = [
            [1,  1, null],
            [2, 10, null],
            [3, 15, null],
            [4, 11, null],
            [5,  9, "Today"],
            [6,  7, null],
            [7,  5, null],
            [8,  3, null],
            [9,  1, null]
        ];
        
        var data = new google.visualization.DataTable();
        data.addColumn("number", "day #");
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
                width: "90%", 
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
            legend : "none"
        };
        
        var chart = new google.visualization.LineChart(document.getElementById("curve_chart"));
        chart.draw(data, options);
    }
    </script>


<?php
    }

}n