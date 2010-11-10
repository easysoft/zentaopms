<?php
/**
 * The router file of ZenTaoPMS.
 *
 * All request should be routed by this router.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
error_reporting(0);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app    = router::createApp('pms', dirname(dirname(__FILE__)));
$common = $app->loadCommon();

/* Check the reqeust is getconfig or not. Check installed or not. */
if(isset($_GET['mode']) and $_GET['mode'] == 'getconfig') die($app->exportConfig());  // 
if(!isset($config->installed) or !$config->installed) die(header('location: install.php'));

/* Run the app. */
$app->parseRequest();
$common->checkPriv();
$app->loadModule();
$common->printRunInfo($startTime);
