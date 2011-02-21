<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<div id='divider'></div>
<div class='yui-d0'>
  <iframe frameborder='0' name='hiddenwin' scrolling='no' class='<?php $config->debug ? print("debugwin") : print('hidden')?>'></iframe>
</div>
<div id='footer' class='yui-d0'>
  <div class='half-left' id='crumbs'><?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?></div>
  <div class='half-right a-right padding-5px'>
    <span id='poweredby'>powered by <a href='http://www.zentao.net' target='_blank'>ZenTaoPMS</a> (<?php echo $config->version;?>)</span>
    <?php echo $lang->sponser;?>
  </div>
</div>
<script laguage='Javascript'>
$().ready(function(){setDebugWin('white')})
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
