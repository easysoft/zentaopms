<?php
$moduleName = $this->app->moduleName;
$methodName = $this->app->methodName;
?>
<script>
<?php 
$this->app->loadLang('chat');
$xxInstalled = $this->app->user->account . 'installed';
?>
<?php if(isset($config->xxserver->installed) and $config->xuanxuan->turnon and !isset($config->xxclient->$xxInstalled) and $config->global->flow == 'full' and $moduleName == 'my' and $methodName == 'index'):?>
var myModalTrigger = new $.zui.ModalTrigger({custom: '<?php echo $lang->chat->xxClientConfirm;?>', title: '<?php echo $lang->chat->zentaoClient;?>'});
var result = myModalTrigger.show();
$.get(createLink('admin', 'ajaxSaveXXStatus', 'type=client'))
<?php endif;?>

<?php if(!isset($config->xxserver->noticed) and $this->app->user->admin and $config->global->flow == 'full' and $moduleName == 'my' and $methodName == 'index'):?>
var myModalTrigger = new $.zui.ModalTrigger({custom: '<?php echo $lang->chat->xxServerConfirm;?>', title: '<?php echo $lang->chat->zentaoClient;?>'});
var result = myModalTrigger.show();
$.get(createLink('admin', 'ajaxSaveXXStatus', 'type=noticed'));
<?php endif;?>
</script>
