<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
  <?php 
  echo '<li id="installed">'  . html::a($this->createLink('extension', 'browse', "type=installed"),   $lang->extension->installed)   . '</li>';
  echo '<li id="deactivated">'. html::a($this->createLink('extension', 'browse', "type=deactivated"), $lang->extension->deactivated) . '</li>';
  echo '<li id="available">'  . html::a($this->createLink('extension', 'browse', "type=available"),   $lang->extension->available )  . '</li>';
  echo '<li id="obtain">  '   . html::a($this->createLink('extension', 'obtain'), $lang->extension->obtain) . '</li>';
  echo '<li>';
  common::printLink('extension', 'upload', '', $lang->extension->upload, '', "class='iframe'");
  echo '</li>';
  echo "<li id='editor'>" . html::a($this->createLink('editor', 'index', 'type=editor'), $lang->editor->common . '&' . $lang->editor->api) . '</li>';
?>
  </ul>
</div>
<script>$('#<?php echo $tab;?>').addClass('active')</script>
