<?php
/**
 * The router file of ZenTaoPMS.
 *
 * All request should be routed by this router.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(0);
define('IN_USE', true);

/* Start output buffer. */
ob_start();

/* Set cookie_httponly. */
ini_set("session.cookie_httponly", 1);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)), 'router');

/* installed or not. */
if(!isset($config->installed) or !$config->installed) die(header('location: install.php'));

/* Run the app. */
$common = $app->loadCommon();

/* Check the reqeust is getconfig or not. */
if(isset($_GET['mode']) and $_GET['mode'] == 'getconfig') die(helper::removeUTF8Bom($app->exportConfig()));

/* Check for need upgrade. */
$config->installedVersion = $common->loadModel('setting')->getVersion();
if(((is_numeric($config->version[0]) and is_numeric($config->installedVersion[0])) or $config->version[0] == $config->installedVersion[0]) and version_compare($config->version, $config->installedVersion, '>')) die(header('location: upgrade.php'));

/* Remove install.php and upgrade.php. */
if(file_exists('install.php') or file_exists('upgrade.php'))
{
    $undeleteFiles = array();
    if(file_exists('install.php')) $undeleteFiles[] = '<strong style="color:#ed980f">install.php</strong>';
    if(file_exists('upgrade.php')) $undeleteFiles[] = '<strong style="color:#ed980f">upgrade.php</strong>';
    $wwwDir = dirname(__FILE__);
    if($undeleteFiles)
    {
        echo "<html><head><meta charset='utf-8'></head>
            <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>";
        echo "<div style='margin-bottom:8px;'>安全起见，请删除 <strong style='color:#ed980f'>{$wwwDir}</strong> 目录下的 " . join(' 和 ', $undeleteFiles) . " 文件。</div>";
        echo "<div>Please remove " . join(' and ', $undeleteFiles) . " under <strong style='color:#ed980f'>$wwwDir</strong> dir for security reason.</div>";
        die("</td></tr></table></body></html>");
    }
}

/* If client device is mobile and version is pro, set the default view as mthml. */
if($app->clientDevice == 'mobile' and (strpos($config->version, 'pro') === 0 or strpos($config->version, 'biz') === 0) and $config->default->view == 'html') $config->default->view = 'mhtml';
if(!empty($_GET['display']) && $_GET['display'] == 'card') $config->default->view = 'xhtml';

$app->parseRequest();
$common->checkPriv();
$app->loadModule();

/* Flush the buffer. */
echo helper::removeUTF8Bom(ob_get_clean());
