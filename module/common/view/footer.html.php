  </div><?php /* end '.outer' in 'header.html.php'. */ ?>
  <script>setTreeBox()</script>
  <?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
  
  <div id='divider'></div>
  <iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin hidden'></iframe>
<?php $onlybody = zget($_GET, 'onlybody', 'no');?>
<?php if($onlybody != 'yes'):?>
</div><?php /* end '#wrap' in 'header.html.php'. */ ?>
<div id='footer'>
  <div id='crumbs'>
    <?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?>
  </div>
  <div id='poweredby'>
  <a href='http://www.zentao.net' target='_blank' class='text-primary'><i class='icon-zentao'></i> <?php echo $lang->zentaoPMS . $config->version;?></a> &nbsp;
    <?php echo $lang->proVersion;?>
    <?php commonModel::printNotifyLink();?>
  </div>
</div>

<script>
<?php if(!isset($config->global->browserNotice)):?>
browserNotice = '<?php echo $lang->browserNotice?>'
function ajaxIgnoreBrowser(){$.get(createLink('misc', 'ajaxIgnoreBrowser'));}
$(function(){showBrowserNotice()});
<?php endif;?>

<?php if(!isset($config->global->novice) and $this->loadModel('tutorial')->checkNovice() and $config->global->flow == 'full'):?>
novice = confirm('<?php echo $lang->tutorial->novice?>');
$.get(createLink('tutorial', 'ajaxSaveNovice', 'novice=' + (novice ? 'true' : 'false')), function()
{
    if(novice) location.href=createLink('tutorial', 'index');
});
<?php endif;?>

<?php if(!empty($this->config->sso->redirect)):?>
<?php
$ranzhiAddr = $this->config->sso->addr;
$ranzhiURL  = substr($ranzhiAddr, 0, strrpos($ranzhiAddr, '/sys/'));
?>
<?php if(!empty($ranzhiURL)):?>
$(function(){ redirect('<?php echo $ranzhiURL?>', '<?php echo $this->config->sso->code?>'); });
<?php endif;?>
<?php endif;?>
</script>

<?php endif;?>

<script>config.onlybody = '<?php echo $onlybody?>';</script>
<?php 
if($this->loadModel('cron')->runable()) js::execute('startCron()');
if(isset($pageJS)) js::execute($pageJS);  // load the js for current page.

/* Load hook files for current page. */
$extPath      = $this->app->getModuleRoot() . '/common/ext/view/';
$extHookRule  = $extPath . 'footer.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
</body>
</html>
