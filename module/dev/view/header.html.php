<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php if(isset($tab)):?>
<script>$('#mainMenu #<?php echo $tab;?>').addClass('btn-active-text')</script>
<?php endif;?>
