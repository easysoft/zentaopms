<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<div id='divider'></div>
<div class='g'>
  <div class='u-1'><iframe frameborder='0' name='hiddenwin' scrolling='no' class='<?php $config->debug ? print("debugwin") : print('hidden')?>'></iframe></div>
</div>
<table class='cont' id='footer'>
  <tr>
    <td width='50%' id='crumbs'><?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?></td>
    <td id='poweredby'>powered by <a href='http://www.zentao.net' target='_blank'>ZenTaoPMS</a> (<?php echo $config->version;?>)
    <?php echo $lang->sponser;?>
    </td>
  </tr>
</table>
<script laguage='Javascript'>$().ready(function(){setDebugWin('white')}); <?php if(isset($pageJS)) echo $pageJS;?></script>
</body>
</html>
