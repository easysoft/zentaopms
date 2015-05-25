<?php
/**
 * The router file of ZenTaoPMS.
 *
 * All request should be routed by this router.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(0);

/* Start output buffer. */
ob_start();

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)));

/* installed or not. */
if(!isset($config->installed) or !$config->installed) die(header('location: install.php'));

/* Detect mobile. */
$mobile = $app->loadClass('mobile');
if(!$mobile->isTablet() and $mobile->isMobile() and $config->default->view == 'html')
{
    $config->default->view = 'mhtml';
    helper::setViewType();
}

/* Run the app. */
$common = $app->loadCommon();

/* Check the reqeust is getconfig or not. */
if(isset($_GET['mode']) and $_GET['mode'] == 'getconfig') die(helper::removeUTF8Bom($app->exportConfig()));

/* Check for need upgrade. */
$config->installedVersion = $common->loadModel('setting')->getVersion();
if(!(!is_numeric($config->version{0}) and $config->version{0} != $config->installedVersion{0}) and version_compare($config->version, $config->installedVersion, '>')) die(header('location: upgrade.php'));
if(file_exists('install.php') or file_exists('upgrade.php'))
{
    $wwwDir = dirname(__FILE__);
    echo "<html><head><meta charset='utf-8'></head><body>";
    echo "目录 {$wwwDir} 下存在 install.php 和 upgrade.php 文件，为了系统的安全，请您删掉这两个文件。<br />";
    echo "The presence of install.php and upgrade.php file in directory {$wwwDir}, in order to software security, please delete these two files.";
    echo '</body></html>';
    exit;
}

$app->parseRequest();
$common->checkPriv();
$app->loadModule();

/* Flush the buffer. */
echo helper::removeUTF8Bom(ob_get_clean());
