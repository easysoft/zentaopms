<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    common::printLink('extension', 'browse', "type=installed",   "<span class='text'>{$lang->extension->installed}</span>"  , '', "class='btn btn-link' id='installed'");
    common::printLink('extension', 'browse', "type=deactivated", "<span class='text'>{$lang->extension->deactivated}</span>", '', "class='btn btn-link' id='deactivated'");
    common::printLink('extension', 'browse', "type=available",   "<span class='text'>{$lang->extension->available}</span>"  , '', "class='btn btn-link' id='available'");
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('extension', 'upload', '', '<i class="icon-cog"></i> ' . $lang->extension->upload, '', "class='iframe btn btn-link'");?>
    <?php common::printLink('extension', 'obtain', '', '<i class="icon-download-alt"></i> ' . $lang->extension->obtain, '', "class='btn btn-primary'");?>
  </div>
</div>
<script>$('#<?php echo $tab;?>').addClass('btn-active-text')</script>
