<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$webRoot      = $this->app->getWebRoot();
$jsRoot       = $webRoot . "js/";
$themeRoot    = $webRoot . "theme/";
$defaultTheme = $webRoot . 'theme/default/';
$clientTheme  = $this->app->getClientTheme();
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <?php
  $header = isset($header) ? (object)$header : new stdclass();
  if(!isset($header->title))    $header->title = $lang->zentaoMS;
  if(!isset($header->keywords)) $header->keywords = $lang->zentaoKeywords;
  if(!isset($header->desc))     $header->desc     = $lang->zentaoDESC;
  echo "<title>$header->title - $lang->zentaoMS</title>\n";
  echo "<meta name='keywords' content='$header->keywords'>\n";
  echo "<meta name='description' content='$header->desc'>\n";
  ?>
<?php echo js::exportConfigVars();?>
<script src="<?php echo $jsRoot;?>jquery/lib.js" type="text/javascript"></script>
<script src="<?php echo $jsRoot;?>my.js"         type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo $defaultTheme . 'yui.css';?>'   type='text/css' media='screen' />
<link rel='stylesheet' href='<?php echo $defaultTheme . 'style.css';?>' type='text/css' media='screen' />
<?php if(strpos($clientTheme, 'default') === false):?>
<link rel='stylesheet' href='<?php echo $clientTheme . 'style.css';?>'  type='text/css' media='screen' />
<?php endif;?>
<link rel='icon'          href='<?php echo $webRoot;?>favicon.ico' type="image/x-icon" />
<link rel='shortcut icon' href='<?php echo $webRoot;?>favicon.ico' type='image/x-icon' />
<script type="text/javascript">loadFixedCSS();</script>
</head>
<body>
