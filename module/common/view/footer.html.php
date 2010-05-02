<p style='margin-bottom:30px'></p>
<div class='yui-d0' id='hiddenbar'>
  <iframe frameborder='0' name='hiddenwin' id='hiddenwin' class='<?php $config->debug ? print("debugwin") : print('hiddenwin')?>'></iframe>
</div>
<div id='footer' class='yui-d0 yui-t7'>
  <div class='yui-g'>
    <div class='yui-g first' id='crumbs'><?php common::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?></div>
    <div class='yui-g'>
      <div class='yui-u first'> </div>
      <div class='yui-u a-right'>
        <span id='poweredby'>powered by <a href='http://www.zentaoms.com' target='_blank'>ZenTaoPMS</a> (<?php echo $config->version;?>)</span>
      </div>
    </div>
  </div>
</div>
</body>
</html>
