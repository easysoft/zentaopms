<?php
$clientTheme = $this->app->getClientTheme();
$webRoot     = $this->app->getWebRoot();
$jsRoot      = $webRoot . "js/";
$themeRoot   = $webRoot . "theme/";
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title><?php echo $header->title;?></title>
<?php echo js::exportConfigVars();?>
<script src="<?php echo $jsRoot;?>jquery/lib.js" type="text/javascript"></script>
<script src="<?php echo $jsRoot;?>my.js"         type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo $clientTheme . 'yui.css';?>' type='text/css' media='screen' />
<link rel='stylesheet' href='<?php echo $clientTheme . 'style.css';?>' type='text/css' media='screen' />
<style>
.ok{background:green; color:white}
.fail{background:red; color:white}
caption, th, td {padding:10px; font-size:16px}
</style>
<script type="text/javascript">loadFixedCSS();</script>
</head>
<body style='margin-top:50px'>
