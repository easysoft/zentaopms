<?php
/** * Config 主文件
 */
$config = new stdclass;
$config->baseRoot = dirname(__FILE__, 2);
$config->gitlabRoot = 'https://gitlab.zcorp.cc/easycorp/testing/-/blob/master/ui/project';

/* 截图相关配置 */
$config->captureRoot    = $config->baseRoot . '/www/data/capture/';
$config->captureWebRoot = '/data/capture/';

/* 报告相关配置 */
$config->reportRoot    = $config->baseRoot . '/www/data/report';
$config->reportWebRoot = '/data/report/';
$config->reportType    = 'html'; //html or markdown

$config->reportTemplate = array();
$config->reportTemplate['markdown'] = "# {TITLE}";
$config->reportTemplate['html']     = <<<EOT
<html>
  <meta charset="utf-8">
  <head>
    <title>{TITLE}</title>
  </head>
  <body>
    <div style='max-width:1024px;margin:0 auto;'>
EOT;

/*Chorme 浏览器默认配置。*/
$config->chrome = new stdclass;
$config->chrome->host = '';

$config->chrome->options = array();
$config->chrome->options[] = '--no-sandbox';                          // 解决DevToolsActivePort文件不存在的报错
$config->chrome->options[] = 'window-size=1366x768';                  // 指定浏览器分辨率
$config->chrome->options[] = '--disable-gpu';                         // 谷歌文档提到需要加上这个属性来规避bug
//$config->chrome->options[] = '--hide-scrollbars';                   // 隐藏滚动条; 应对一些特殊页面
//$config->chrome->options[] = 'blink-settings=imagesEnabled=false';  // 不加载图片; 提升速度
$config->chrome->options[] = '--ignore-certificate-errors';
//$config->chrome->options[] = '--ignore-ssl-errors';
//$config->chrome->options[] = '--headless';                          // 浏览器不提供可视化页面
$config->chrome->options[] = '--test-type';
$config->chrome->options[] = '--start-maximized';

/* 项目配置 */
$config->defaultAccount  = 'admin';
$config->defaultPassword = '123Qwe!@#';

$config->db = new stdclass;
$config->db->driver   = 'mysql';
$config->db->host     = '127.0.0.1';
$config->db->port     = '3306';
$config->db->name     = 'zentao';
$config->db->user     = 'root';
$config->db->encoding = 'UTF8';
$config->db->password = '123456';
$config->db->prefix   = 'zt_';

$config->selectorAlias  = array();
$config->selectorAlias['xpath'] = 'xpath';
$config->selectorAlias['css']   = 'cssSelector';
$config->selectorAlias['name']  = 'name';
$config->selectorAlias['id']    = 'id';
$config->selectorAlias['class'] = 'className';
$config->selectorAlias['tag']   = 'tagName';
$config->selectorAlias['link']  = 'linkText';

$config->onException = true;

/* Include extension config files. */
if(file_exists(__DIR__ . '/my.php')) include __DIR__ . '/my.php';

$extConfigFiles = glob(dirname(__FILE__) . DS . 'ext' . DS . '*.php');
if($extConfigFiles) foreach($extConfigFiles as $extConfigFile) include $extConfigFile;
