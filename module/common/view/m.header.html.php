<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'm.header.lite.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<div data-role="header">
  <div data-role="navbar">
  </div>
</div>
<?php endif;?>
<div data-role="content">
