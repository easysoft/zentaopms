<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$webRoot      = $this->app->getWebRoot();
$jsRoot       = $webRoot . "js/";
$themeRoot    = $webRoot . "theme/";
$defaultTheme = $webRoot . 'theme/default/';
$langTheme    = $themeRoot . 'lang/' . $app->getClientLang() . '.css';
$clientTheme  = $this->app->getClientTheme();
$onlybody     = zget($_GET, 'onlybody', 'no');
?>
<!DOCTYPE html>
<html lang='<?php echo $app->getClientLang();?>'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <?php
  echo html::title($title . ' - ' . $lang->zentaoPMS);
  js::exportConfigVars();
  echo '<script>config.onlybody = "' . $onlybody . '";</script>';
  if($config->debug)
  {
      $timestamp = time();
      js::import($jsRoot . 'jquery/lib.js');
      js::import($jsRoot . 'zui/min.js?t=' . $timestamp);
      js::import($jsRoot . 'my.full.js?t=' . $timestamp);

      css::import($themeRoot . 'zui/css/min.css?t=' . $timestamp);
      css::import($defaultTheme . 'style.css?t=' . $timestamp);

      css::import($langTheme);
      if(strpos($clientTheme, 'default') === false) css::import($clientTheme . 'style.css?t=' . $timestamp);
  }
  else
  {
      js::import($jsRoot . 'all.js');
      css::import($defaultTheme . $this->cookie->lang . '.' . $this->cookie->theme . '.css');
  }

  if(!defined('IN_INSTALL') and commonModel::isTutorialMode())
  {
      $wizardModule    = defined('WIZARD_MODULE') ? WIZARD_MODULE : $this->moduleName;
      $wizardMethod    = defined('WIZARD_METHOD') ? WIZARD_METHOD : $this->methodName;
      $requiredFields  = '';
      if(isset($config->$wizardModule->$wizardMethod->requiredFields)) $requiredFields = str_replace(' ', '', $config->$wizardModule->$wizardMethod->requiredFields);
      echo "<script>window.TUTORIAL = {'module': '$wizardModule', 'method': '$wizardMethod', tip: '$lang->tutorialConfirm'}; if(config) config.requiredFields = '$requiredFields'; </script>";
  }

  if(isset($pageCSS)) css::internal($pageCSS);

  echo html::favicon($webRoot . 'favicon.ico');
  ?>
<!--[if lt IE 10]>
<?php js::import($jsRoot . 'jquery/placeholder/min.js'); ?>
<![endif]-->
</head>
<body>
