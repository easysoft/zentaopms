<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 1947 2011-06-29 11:58:03Z wwccss $
 */
?>
<?php include '../../../common/view/header.html.php';?>
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
<?php echo $this->fetch('block', 'dashboard', 'module=my');?>
<?php include '../../../common/view/footer.html.php';?>
