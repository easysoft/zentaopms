<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$webRoot      = $this->app->getWebRoot();
$jsRoot       = $webRoot . "js/";
$themeRoot    = $webRoot . "theme/";
$defaultTheme = $webRoot . 'theme/default/';
$langTheme    = $themeRoot . 'lang/' . $app->getClientLang() . '.css';
$clientTheme  = $this->app->getClientTheme();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8' />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php
  echo html::title($title . ' - ' . $lang->zentaoPMS);

  js::exportConfigVars();
  if($config->debug)
  {
      js::import($jsRoot . 'jquery/mobile/jquery-1.10.1.min.js', $config->version);
      js::import($jsRoot . 'm.my.full.js', $config->version);
      js::import($jsRoot . 'jquery/mobile/jquery.mobile.min.js', $config->version);

      css::import($defaultTheme . 'jquery.mobile.css', $config->version);
      css::import($defaultTheme . 'm.style.css', $config->version);
      css::import($langTheme, $config->version);
  }

  if(isset($pageCss)) css::internal($pageCss);

  echo html::icon($webRoot . 'favicon.ico');
  ?>
</head>
<body>
<div data-role="page">
