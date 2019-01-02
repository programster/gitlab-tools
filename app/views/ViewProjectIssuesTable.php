<?php

/* 
 * A view for a table of issues that relate to a specific project.
 */

class ViewProjectIssuesTable extends AbstractView
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
            <th>Time Spent</th>
            <th>Estimate</th>
            <th>Assignee</th>
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
            <td><?= $issue->getTimeStats()->getHumanTotalTimeSpent(); ?></td>
            <td><?= $issue->getTimeStats()->getHumanTimeEstimate(); ?></td>
            <td><?= $issue->getAssignee(); ?></td>
        </tr>
        <?php 
        } 
        ?>
       
    </tbody>
</table>




<?php
    }

}