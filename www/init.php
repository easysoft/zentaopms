<?php
/**
 * 本文件创建一个 app 示例，并且通过执行 $app->loadCommon() 方法创建名为 tester 的commonModel对象。
 * This file build a app instance and provide a instance of commonModel named as tester by $app->loadCommon().
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guanxing <guanxiying@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(0);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

$app    = router::createApp('pms', dirname(dirname(__FILE__)), 'router');
$tester = $app->loadCommon();
