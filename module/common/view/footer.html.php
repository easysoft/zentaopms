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
<?php endif;?>

<?php if(!isset($config->global->browserNotice)):?>
<script>
browserNotice = '<?php echo $lang->browserNotice?>'
function ajaxIgnoreBrowser(){$.get(createLink('misc', 'ajaxIgnoreBrowser'));}
$(function(){showBrowserNotice()});
</script>
<?php endif;?>
<?php if(!isset($config->global->novice) and $this->loadModel('tutorial')->checkNovice()):?>
<script>
novice = confirm('<?php echo $lang->tutorial->novice?>');
$.get(createLink('tutorial', 'ajaxSaveNovice', 'novice=' + (novice ? 'true' : 'false')), function()
{
    if(novice) location.href=createLink('tutorial', 'index');
});
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
