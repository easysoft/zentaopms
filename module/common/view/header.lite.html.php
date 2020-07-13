<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$clientLang   = $app->getClientLang();
$webRoot      = $this->app->getWebRoot();
$jsRoot       = $webRoot . "js/";
$themeRoot    = $webRoot . "theme/";
$defaultTheme = $webRoot . 'theme/default/';
$langTheme    = $themeRoot . 'lang/' . $clientLang . '.css';
$clientTheme  = $this->app->getClientTheme();
$onlybody     = zget($_GET, 'onlybody', 'no');
?>
<!DOCTYPE html>
<html lang='<?php echo $clientLang;?>'>
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

      css::import($themeRoot . 'zui/css/min.css?t=' . $timestamp);
      css::import($defaultTheme . 'style.css?t=' . $timestamp);

      css::import($langTheme);
      if(strpos($clientTheme, 'default') === false) css::import($clientTheme . 'style.css?t=' . $timestamp);

      js::import($jsRoot . 'jquery/lib.js');
      js::import($jsRoot . 'zui/min.js?t=' . $timestamp);
      if($clientLang === 'ja') js::import($jsRoot . 'zui/lang.' . $clientLang . '.min.js?t=' . $timestamp);
      js::import($jsRoot . 'my.full.js?t=' . $timestamp);

  }
  else
  {
      $minCssFile = $defaultTheme . $this->cookie->lang . '.' . $this->cookie->theme . '.css';
      if(!file_exists($this->app->getThemeRoot() . 'default/' . $this->cookie->lang . '.' . $this->cookie->theme . '.css')) $minCssFile = $defaultTheme . 'en.' . $this->cookie->theme . '.css';
      css::import($minCssFile);
      js::import($jsRoot . 'all.js');
      if($clientLang === 'ja') js::import($jsRoot . 'zui/lang.' . $clientLang . '.min.js');
  }
  if($this->app->getViewType() == 'xhtml') css::import($defaultTheme . 'x.style.css');

  if(defined('IN_USE') and commonModel::isTutorialMode())
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
<?php
/* Load hook files for current page. */
$extPath      = $this->app->getModuleRoot() . '/common/ext/view/';
$extHookRule  = $extPath . 'header.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
</head>
<body>
