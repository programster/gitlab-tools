<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewJumbotron extends AbstractView
{
    private $m_title;
    private $m_body;
    
    
    public function __construct(string $title, string $body) {
        $this->m_title = $title;
        $this->m_body = $body;
    }
    
    
    protected function renderContent() {
?>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1><?= $this->m_title; ?></h1>
  <p><?= $this->m_body; ?></p> 
</div>

<?php

    }

}
