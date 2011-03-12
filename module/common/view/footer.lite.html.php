</div>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='<?php $config->debug ? print("debugwin") : print('hidden')?>'></iframe>
<script laguage='Javascript'>
$().ready(function(){setDebugWin('white')})
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
