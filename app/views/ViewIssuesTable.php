<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewIssuesTable extends AbstractView
{
    private $m_issues;
    
    
    public function __construct(Issue ...$issues)
    {
        $this->m_issues = $issues;
    }
    
    
    protected function renderContent() 
    {
?>



<table class="table table-hover">
    <thead>
        <tr>
            <th>Title</th>
            <th>Project</th>
            <th>Time Spent</th>
            <th>Estimate</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        
        <?php 
        foreach ($this->m_issues as $issue)
        {
            /* @var $issue Issue */
            $project = $issue->getProject();
        ?>
        <tr>
            <td><a href="<?= $issue->getWebUrl(); ?>"><?= $issue->getTitle() ;?></a></td>
            <td><a href="/projects/<?= $project->getId(); ?>"><?= $project->getName(); ?></td>
            <td><?= $issue->getTimeStats()->getHumanTotalTimeSpent(); ?></td>
            <td><?= $issue->getTimeStats()->getHumanTimeEstimate(); ?></td>
            <td><?= $issue->getState(); ?></td>
        </tr>
        <?php 
        } 
        ?>
       
    </tbody>
</table>




<?php
    }

}