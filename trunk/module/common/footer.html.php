<iframe frameborder='0' name='hiddenwin' id='hiddenwin' style='<?php if($config->debug) echo "display:block; margin:10px; width:90%; height:100px; border:1px solid #fff";?>' src='<?php echo $this->createLink('index', 'ping');?>'></iframe>
<div id='footer' class='yui-d0'>
powered by <a href='http://www.zentao.cn' target='_blank'>ZenTaoPMS</a> <span style='font-size:8px;'>(<?php echo $config->version, ' ', $config->svn->revision, ' ', $config->svn->lastDate ;?>)</span>.
</div>
</body>
</html>
