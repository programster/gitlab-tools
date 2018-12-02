<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewNavLink extends AbstractView
{
    private $m_name;
    private $m_location;
    private $m_isActive;
    
    
    public function __construct(string $name, string $location, bool $isActive)
    {
        $this->m_name = $name;
        $this->m_location = $location;
        $this->m_isActive = $isActive;
    }
    

    protected function renderContent() 
    {
?>

<li class="nav-item">
    <a class="nav-link<?php if ($this->m_isActive) { print " active"; }?>" href="<?= $this->m_location; ?>"><?= $this->m_name; ?></a>
</li>

<?php
    }

}

