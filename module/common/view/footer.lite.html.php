<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if(empty($config->noHiddenwin)):?>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin hidden'></iframe>
<?php endif;?>
<?php if($this->loadModel('cron')->runable()) js::execute('startCron()');?>
<script>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
