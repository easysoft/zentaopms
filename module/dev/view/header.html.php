<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php 
    common::printLink('dev', 'api', "", "<span class='text'>{$lang->dev->api}</span>", '', "id='api' class='btn btn-link'");
    common::printLink('dev', 'db', "",  "<span class='text'>{$lang->dev->db}</span>",  '', "id='db' class='btn btn-link'");
    common::printLink('editor', 'index', 'type=editor', "<span class='text'>{$lang->dev->editor}</span>", '', "id='editor' class='btn btn-link'");
    ?>
  </div>
</div>
<script>$('#mainMenu #<?php echo $tab;?>').addClass('btn-active-text')</script>
