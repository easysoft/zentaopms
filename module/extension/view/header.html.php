<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='featurebar'>
  <?php 
  echo '<span id="installed">'  . html::a(inlink('browse', "type=installed"),   $lang->extension->installed)   . '</span>';
  echo '<span id="deactivated">'. html::a(inlink('browse', "type=deactivated"), $lang->extension->deactivated) . '</span>';
  echo '<span id="available">'  . html::a(inlink('browse', "type=available"),   $lang->extension->available )  . '</span>';
  echo '<span id="obtain">  '   . html::a(inlink('obtain'), $lang->extension->obtain) . '</span>';
  echo '<span id="upload" >'    . html::a(inlink('upload'),   $lang->extension->upload, '', "class='iframe'") . '</span>';
  ?>
  <script>$('#<?php echo $tab;?>').addClass('active')</script>
</div>
