<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php include 'debug.html.php';?>
<script laguage='Javascript'>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
