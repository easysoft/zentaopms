</div>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<div id='divider'></div>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='<?php $config->debug ? print("debugwin") : print('hidden')?>'></iframe>
<div id='footer'>
  <table class='cont' >
    <tr>
      <td class='w-p50 'id='crumbs'><?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?></td>
      <td class='a-right' id='poweredby'>
        <span>powered by <a href='http://www.zentao.net' target='_blank'>ZenTaoPMS</a> (<?php echo $config->version;?>)</span>
        <?php echo $lang->donate;?>
      </td>
    </tr>
  </table>
</div>
<script laguage='Javascript'>
$().ready(function(){setDebugWin('white')})
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
<?php 
$extPath     = dirname(dirname(dirname(realpath($viewFile)))) . '/common/ext/view/';
$extHookFile = $extPath . 'footer.*.hook.php';
$files = glob($extHookFile);
if($files) foreach($files as $file) include $file;
?>
</body>
</html>
