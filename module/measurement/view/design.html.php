<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php if($measurement->method == 'sql') include 'designsql.html.php';?>
<?php if($measurement->method == 'php') include 'designphp.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
<script>
$('#subNavbar ul li[data-id="settips"]').removeClass('active');
$('#subNavbar ul li[data-id="template"]').removeClass('active');
</script>
