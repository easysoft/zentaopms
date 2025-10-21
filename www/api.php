<?php
/**
 * The api router file of ZenTaoPMS.
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(0);
define('RUN_MODE', 'api');
/* Start output buffer. */
ob_start();

/* Load the framework. */
include '../framework/api/router.class.php';
include '../framework/api/entry.class.php';
include '../framework/api/helper.class.php';
include '../framework/api/control.class.php';
include '../framework/model.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)), 'api');

/* Run the app. */
$common = $app->loadCommon();

/* Set default params. */
$config->requestType = 'GET';
$config->default->view = 'json';

try
{
    $app->parseRequest();

    /* APIv1 load entries, not control directly. */
    if($app->apiVersion != 'v1') $common->checkPriv();

    $app->loadModule();
}
catch (EndResponseException $endResponseException)
{
    echo $endResponseException->getContent();
}

/* Flush the buffer. */
echo $app->formatData(helper::removeUTF8Bom(ob_get_clean()));