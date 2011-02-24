<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$webRoot      = $this->app->getWebRoot();
$jsRoot       = $webRoot . "js/";
$themeRoot    = $webRoot . "theme/";
$defaultTheme = $webRoot . 'theme/default/';
$langTheme    = $themeRoot . 'lang/' . $app->getClientLang() . '.css';
$clientTheme  = $this->app->getClientTheme();
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <?php
  $header = isset($header) ? (object)$header : new stdclass();
  if(!isset($header->title))    $header->title    = $lang->ZenTaoPMS;
  if(!isset($header->keywords)) $header->keywords = $lang->zentaoKeywords;
  if(!isset($header->desc))     $header->desc     = $lang->zentaoDESC;

  echo html::title($header->title . '-' . $lang->ZenTaoPMS);
  echo html::meta('keywords',    $header->keywords);
  echo html::meta('description', $header->desc);

  js::exportConfigVars();
  js::import($jsRoot . 'jquery/lib.js', $config->version);
  js::import($jsRoot . 'my.js',         $config->version);

  css::import($defaultTheme . 'yui.css',   $config->version);
  css::import($defaultTheme . 'style.css', $config->version);
  css::import($langTheme, $config->version);
  if(strpos($clientTheme, 'default') === false) css::import($clientTheme . 'style.css', $config->version);

  echo html::icon($webRoot . 'favicon.ico');
  ?>
<script type="text/javascript">loadFixedCSS();</script>
</head>
<body>
