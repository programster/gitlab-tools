<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewProjectsTable extends AbstractView
{
    private $m_projects;
    
    
    public function __construct(Project ...$projects)
    {
        $this->m_projects = $projects;
    }
    
    
    protected function renderContent() 
    {
?>



<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        
        <?php 
        foreach ($this->m_projects as $project)
        {
        ?>
        <tr>
            <td><?= $project->getId() ;?></td>
            <td><a href="/projects/<?= $project->getId(); ?>"><?= $project->getName(); ?></td>
        </tr>
        <?php 
        } 
        ?>
       
    </tbody>
</table>




<?php
    }

}