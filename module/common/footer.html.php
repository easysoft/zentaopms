<!--[if lte IE 6]><br /><![endif]-->
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' style='<?php if($config->debug) echo "display:block; margin:10px; width:90%; height:100px; border:1px solid #fff";?>' src='<?php echo $this->createLink('index', 'ping');?>'></iframe>
<div id='footer' class='yui-d0 yui-t7'>
  <div class='yui-g'>
    <div class='yui-g first'>
      <?php
      list($menuLabel, $module, $method) = explode('|', $lang->menu->index);
      echo html::a($this->createLink($module, $method), $lang->zentaoMS) . $lang->arrow;
      if($moduleName != 'index')
      {
          list($menuLabel, $module, $method) = explode('|', $lang->menu->$mainMenu);
          echo html::a($this->createLink($module, $method), $menuLabel);
      }
      else
      {
          echo $lang->index->common;
      }
      if(isset($position))
      {
          echo $lang->arrow;
          foreach($position as $key => $link)
          {
              echo $link;
              if(isset($position[$key + 1])) echo $lang->arrow;
          }
      }
      ?>
    </div>
    <div class='yui-g'>
      <div class='yui-u first'></div>
      <div class='yui-u a-right' style='padding-right:10px; font-size:8px'>
        powered by <a href='http://www.zentao.cn' target='_blank'>ZenTaoPMS</a> (<?php echo $config->version;?>)
      </div>
    </div>
  </div>
</div>
</body>
</html>
