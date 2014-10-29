<?php
/**
 * The router file of ZenTaoPMS.
 *
 * All request should be routed by this router.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
if(!$mobile->isTablet() and $mobile->isMobile())
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

$app->parseRequest();
$common->checkPriv();
$app->loadModule();

/* Flush the buffer. */
echo helper::removeUTF8Bom(ob_get_clean());
