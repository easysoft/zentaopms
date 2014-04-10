<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if($config->debug) include 'debug.html.php';?>
<script laguage='Javascript'>
$().ready(function(){setDebugWin('white')})
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
