<?php

class ViewTemplate extends AbstractView
{
    private $m_footer;
    private $m_navbar;
    private $m_tabTitle;
    private $m_jumbotron;
    private $m_content;
    
    public function __construct(
        string $tabTitle, 
        ViewJumbotron $jumbotron, 
        string $navbar, 
        string $content,
        ViewFooter $footer
    )
    {
        $this->m_navbar = $navbar;
        $this->m_footer = $footer;
        $this->m_tabTitle = $tabTitle;
        $this->m_content = $content;
        $this->m_jumbotron = $jumbotron;
    }

    protected function renderContent()        
    {
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= $this->m_tabTitle; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <style>
  .fakeimg {
      height: 200px;
      background: #aaa;
  }
  </style>
</head>
<body>
    <?= $this->m_jumbotron; ?>
    <?= $this->m_navbar; ?>
    <?= $this->m_content; ?>
    <?= $this->m_footer; ?>
</body>
</html>

<?php
    }
}
