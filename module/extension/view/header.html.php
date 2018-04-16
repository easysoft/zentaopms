<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php 
    common::printLink('extension', 'browse', "type=installed",   "<span class='text'>{$lang->extension->installed}</span>"  , '', "class='btn btn-link' id='installed'");
    common::printLink('extension', 'browse', "type=deactivated", "<span class='text'>{$lang->extension->deactivated}</span>", '', "class='btn btn-link' id='deactivated'");
    common::printLink('extension', 'browse', "type=available",   "<span class='text'>{$lang->extension->available}</span>"  , '', "class='btn btn-link' id='available'");
    common::printLink('extension', 'upload', '', "<span class='text'>{$lang->extension->upload}</span>", '', "class='iframe btn btn-link'");
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('extension', 'obtain', '', '<i class="icon-download-alt"></i> ' . $lang->extension->obtain, '', "class='btn'");?>
  </div>
</div>
<script>$('#<?php echo $tab;?>').addClass('btn-active-text')</script>
