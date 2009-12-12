<!--[if lte IE 6]><br /><![endif]-->
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' class='<?php $config->debug ? print("debugwin") : print('hiddenwin')?>' src='<?php echo $this->createLink('index', 'ping');?>'></iframe>
<div id='footer' class='yui-d0 yui-t7'>
  <div class='yui-g'>
    <div class='yui-g first' id='bread'> <?php common::printBreadMenu($this->moduleName, $position); ?></div>
    <div class='yui-g'>
      <div class='yui-u first'></div>
      <div class='yui-u a-right' id='footer-right'>
        <span id='poweredby'>powered by <a href='http://www.zentao.cn' target='_blank'>ZenTaoPMS</a> (<?php echo $config->version;?>)</span>
      </div>
    </div>
  </div>
</div>
</body>
</html>
