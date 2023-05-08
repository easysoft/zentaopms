<?php
/**
 * The cron-worker file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lu Fei <lufei@easycorp.ltd>
 * @package     cron
 * @link        https://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(0);

/* Load the framework. */
include '../../framework/router.class.php';
include '../../framework/control.class.php';
include '../../framework/model.class.php';
include '../../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(__DIR__, 2), 'router');

/* Run the app. */
$app->setStartTime($startTime);
$common = $app->loadCommon();

$app->moduleName = 'cron';
$app->methodName = 'ajaxExec';
$app->setControlFile();
$app->loadModule();
