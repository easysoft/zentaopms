<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php // common::printAdminSubMenu('dev');?></div>
</div>
<?php if(isset($tab)):?>
<script>$('#mainMenu #<?php echo $tab;?>').addClass('btn-active-text')</script>
<?php endif;?>
