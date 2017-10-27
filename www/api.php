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
$app = router::createApp('pms', dirname(dirname(__FILE__)), 'router');

/* Run the app. */
$common = $app->loadCommon();

/* Check entry. */
$common->checkEntry();

/* Set default params. */
$config->requestType   = 'GET';
$config->default->view = 'json';

$app->parseRequest();
$common->checkPriv();
$app->loadModule();

$output = json_decode(ob_get_clean());
$output = json_encode($output->data);

unset($_SESSION['ENTRY_CODE']);
unset($_SESSION['VALID_ENTRY']);

/* Flush the buffer. */
echo helper::removeUTF8Bom($output);
