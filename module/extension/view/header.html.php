<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
  <?php 
  echo '<span>'  . $lang->extension->common . '：</span>';
  echo '<span id="installed">'  . html::a($this->createLink('extension', 'browse', "type=installed"),   $lang->extension->installed)   . '</span>';
  echo '<span id="deactivated">'. html::a($this->createLink('extension', 'browse', "type=deactivated"), $lang->extension->deactivated) . '</span>';
  echo '<span id="available">'  . html::a($this->createLink('extension', 'browse', "type=available"),   $lang->extension->available )  . '</span>';
  echo '<span id="obtain">  '   . html::a($this->createLink('extension', 'obtain'), $lang->extension->obtain) . '</span>';
  common::printLink('extension', 'upload', '', $lang->extension->upload, '', "class='iframe'");
  echo "<span id='editor'>" . html::a($this->createLink('editor', 'index', 'type=editor'), $lang->editor->common . '&' . $lang->editor->api) . '</span>';
?>
  </div>
  <script>$('#<?php echo $tab;?>').addClass('active')</script>
</div>
