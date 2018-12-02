<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ViewNavbar extends AbstractView
{
    private $m_links;
    
    public function __construct(ViewNavLink ...$links)
    {
        $this->m_links = $links;
    }
    
    
    public function renderContent() 
    {
?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
        <?php foreach ($this->m_links as $link) { print $link; } ?>
    </ul>
  </div>  
</nav>


<?php
    }
}
