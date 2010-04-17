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
  <?php
  if(!isset($header)) $header = new stdClass();
  $header = (object)$header;
  if(isset($header->title))   echo "<title>$header->title - $lang->zentaoMS</title>\n";
  if(isset($header->keyword)) echo "<meta name='keywords' content='$header->keyword'>\n";
  if(isset($header->desc))    echo "<meta name='description' content='$header->desc'>\n";
  ?>
<?php echo js::exportConfigVars();?>
<script src="<?php echo $jsRoot;?>jquery/lib.js" type="text/javascript"></script>
<script src="<?php echo $jsRoot;?>my.js"         type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo $clientTheme . 'yui.css';?>' type='text/css' media='screen' />
<link rel='stylesheet' href='<?php echo $clientTheme . 'style.css';?>' type='text/css' media='screen' />
<link rel='icon'          href='<?php echo $webRoot;?>favicon.ico' type="image/x-icon" />
<link rel='shortcut icon' href='<?php echo $webRoot;?>favicon.ico' type='image/x-icon' />
<script type="text/javascript">loadFixedCSS();</script>
</head>
<body>
