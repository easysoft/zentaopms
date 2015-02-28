  </div><?php /* end '.outer' in 'header.html.php'. */ ?>
  <script>setTreeBox()</script>
  <?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
  
  <div id='divider'></div>
  <iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin hidden'></iframe>
<?php $onlybody = zget($_GET, 'onlybody', 'no');?>
<?php if($onlybody != 'yes'):?>
</div><?php /* end '#wrap' in 'header.html.php'. */ ?>
<div id='footer'>
  <div id="crumbs">
    <?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?>
  </div>
  <div id="poweredby">
  <a href='http://www.zentao.net' target='_blank' class='text-primary'><i class='icon-zentao'></i> <?php echo $lang->zentaoPMS . $config->version;?></a> &nbsp;
    <?php echo $lang->proVersion;?>
    <?php commonModel::printNotifyLink();?>
    <?php commonModel::printQRCodeLink();?>
  </div>
</div>
<?php endif;?>
<?php 
js::set('onlybody', $onlybody);           // set the onlybody var.
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
