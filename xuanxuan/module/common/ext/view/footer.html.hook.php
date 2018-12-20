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
result = confirm('<?php echo $lang->chat->xxClientConfirm?>');
$.get(createLink('admin', 'ajaxSaveXXStatus', 'type=client'), function()
{
    if(result) $('#footer #poweredBy a:last-child').click();
});
<?php endif;?>

<?php if(!isset($config->xxserver->noticed) and $this->app->user->admin and $config->global->flow == 'full' and $moduleName == 'my' and $methodName == 'index'):?>
result = confirm('<?php echo $lang->chat->xxServerConfirm?>');
$.get(createLink('admin', 'ajaxSaveXXStatus', 'type=noticed'), function()
{
    if(result) location.href=createLink('admin', 'xuanxuan');
});
<?php endif;?>
</script>
