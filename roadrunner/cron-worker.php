<?php
/**
 * The api router file of ZenTaoPMS.
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(0);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(__DIR__), 'router');

// $_GET[$app->config->moduleVar] = 'cron';
// $_GET[$app->config->methodVar] = 'ajaxExec';
// $_SERVER['REQUEST_METHOD'] = 'GET';
// $_SERVER['REQUEST_URI'] = '/';
// $_GET['once'] = true;

// $app->moduleName = 'cron';
// $app->methodName = 'ajaxExec';

/* Run the app. */
$common = $app->loadCommon();

// var_dump(1);
/* Check entry. */
// $common->checkEntry();
// var_dump(2);
// $common->loadConfigFromDB();
// var_dump(3);

/* Set default params. */
// if(!$app->version) $config->requestType = 'GET';
// $config->default->view = 'json';

// $app->parseRequest();

// /* Old version need check priv here, new version check priv in entry. */
// if(!$app->version) $common->checkPriv();

// $app->moduleName = 'cron';
// $app->methodName = 'ajaxExec';

// $app->parseRequest();
// $common->checkPriv();
$app->moduleName = 'cron';
$app->methodName = 'ajaxExec';
$app->setControlFile();
$app->loadModule();
