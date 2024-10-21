<?php
/** * Config 主文件
 */
$config->uitest = new stdclass();
$config->uitest->langClient = getenv('ZT_DEFAULT_LANG') ? getenv('ZT_DEFAULT_LANG') : 'zh-cn';

$config->uitest->baseRoot = dirname(__FILE__, 2);

/* 截图相uitest->关配置 */
$config->uitest->captureRoot    = $config->uitest->baseRoot . '/www/data/capture/';
$config->uitest->captureWebRoot = '/data/capture/';

/* 报告相uitest->关配置 */
$config->uitest->reportRoot    = $config->uitest->baseRoot . '/www/data/report';
$config->uitest->reportWebRoot = '/data/report/';
$config->uitest->reportType    = 'html'; //html or markdown

$config->uitest->reportTemplate = array();
$config->uitest->reportTemplate['markdown'] = "# {TITLE}";
$config->uitest->reportTemplate['html']     = <<<EOT
<html>
  <meta charset="utf-8">
  <head>
    <title>{TITLE}</title>
  </head>
  <body>
    <div style='max-width:1024px;margin:0 auto;'>
EOT;

/*Chorme 浏览器默认配置。*/
$config->uitest->chrome = new stdclass;
$config->uitest->chrome->host = '';

$config->uitest->chrome->options = array();
$config->uitest->chrome->options[] = '--no-sandbox';                          // 解决DevToolsActivePort文件不存在的报错
$config->uitest->chrome->options[] = 'window-size=1366x768';                  // 指定浏览器分辨率
$config->uitest->chrome->options[] = '--disable-gpu';                         // 谷歌文档提到需要加上这个属性来规避bug
//$config->uitest->chrome->options[] = '--hide-scrollbars';                   // 隐藏滚动条; 应对一些特殊页面
//$config->uitest->chrome->options[] = 'blink-settings=imagesEnabled=false';  // 不加载图片; 提升速度
$config->uitest->chrome->options[] = '--ignore-certificate-errors';
//$config->uitest->chrome->options[] = '--ignore-ssl-errors';
//$config->uitest->chrome->options[] = '--headless';                          // 浏览器不提供可视化页面
$config->uitest->chrome->options[] = '--test-type';
$config->uitest->chrome->options[] = '--start-maximized';

$config->uitest->exitBrowserOnException = true;
$extConfigFiles = glob(dirname(__FILE__) . DS . 'ext' . DS . '*.php');
if($extConfigFiles) foreach($extConfigFiles as $extConfigFile) include $extConfigFile;
