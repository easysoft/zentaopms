<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<div id='divider'></div>
<div class='g'>
  <div class='u-1'><iframe frameborder='0' name='hiddenwin' scrolling='no' class='<?php $config->debug ? print("debugwin") : print('hidden')?>'></iframe></div>
</div>
<div class='g' id='footer'>
  <div class='u-1-2'>
    <div class='cont a-left' id='crumbs'><?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?></div>
  </div>
  <div class='u-1-2'>
    <div class='cont a-right' id='poweredby'>powered by <a href='http://www.zentao.net' target='_blank'>ZenTaoPMS</a> (<?php echo $config->version;?>)
    <?php echo $lang->sponser;?>
    </div>
  </div>
</div>
<script laguage='Javascript'>$().ready(function(){setDebugWin('white')}); <?php if(isset($pageJS)) echo $pageJS;?></script>
</body>
</html>
