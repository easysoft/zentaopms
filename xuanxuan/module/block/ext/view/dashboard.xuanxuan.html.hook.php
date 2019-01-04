<script>
<?php 
$this->app->loadLang('chat');
$account = $this->app->user->account;
$xxInstalled = $this->app->user->account . 'installed';
?>
<?php if(isset($config->xxserver->installed) and $config->xuanxuan->turnon and !isset($config->xxclient->$xxInstalled) and $config->global->flow == 'full' ):?>
var myModalTrigger = new $.zui.ModalTrigger({custom: '<?php echo $lang->chat->xxClientConfirm;?>', title: '<?php echo $lang->chat->zentaoClient;?>'});
var result = myModalTrigger.show();
<?php $this->loadModel('setting')->setItem("system.common.xxclient.{$account}installed", 1);?>
<?php endif;?>

<?php if(!isset($config->xxserver->noticed) and $this->app->user->admin and $config->global->flow == 'full' and $config->$module->block->initVersion >= '2'):?>
var myModalTrigger = new $.zui.ModalTrigger({custom: '<?php echo $lang->chat->xxServerConfirm;?>', title: '<?php echo $lang->chat->zentaoClient;?>'});
var result = myModalTrigger.show();
<?php $this->loadModel('setting')->setItem("system.common.xxserver.noticed", 1);?>
<?php endif;?>
</script>
