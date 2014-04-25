  </div><?php /* end '.outer' in 'header.html.php'. */ ?>
  <?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
  
  <div id='divider'></div>
<?php $onlybody = zget($_GET, 'onlybody', 'no');?>
<?php if($onlybody != 'yes'):?>
</div><?php /* end '#wrap' in 'header.html.php'. */ ?>
<div id='footer'>
  <?php include 'debug.html.php';?>
  <div id="crumbs">
    <?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?>
  </div>
  <div id="poweredby">
    <span><a href='http://www.zentao.net' target='_blank'><?php echo $lang->zentaoPMS . $config->version;?></a></span> &nbsp;
    <?php echo $lang->proVersion;?>
    <?php commonModel::printNotifyLink();?>
    <?php commonModel::printQRCodeLink();?>
  </div>
</div>
<?php endif;?>
<?php 
js::set('onlybody', $onlybody);           // set the onlybody var.
if(isset($pageJS)) js::execute($pageJS);  // load the js for current page.

/* Load hook files for current page. */
$extPath      = dirname(dirname(dirname(realpath($viewFile)))) . '/common/ext/view/';
$extHookRule  = $extPath . 'footer.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
</body>
</html>
