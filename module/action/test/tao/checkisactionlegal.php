#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->checkIsActionLegal().
timeout=0
cid=14940

- 测试文档在有权限的文档列表中，返回true @1
- 测试产品属于影子产品，返回false @0
- 测试api文档不在有权限的api文档列表中，返回false @0
- 测试文档库不在有权限的文档库列表中，返回false @0
- 测试其他action，返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('user1');

global $tester;
$actionModel = $tester->loadModel('action');

$action = new stdclass();
$action->objectType = 'doc';
$action->objectID   = 1;

$docList        = array(1 => 1);
$apiList        = array(1 => 1);
$docLibList     = array(1 => 1);
$shadowProducts = array(1 => 1);

r($actionModel->checkIsActionLegal($action, $shadowProducts, $docList, $apiList, $docLibList)) && p() && e('1'); //测试文档在有权限的文档列表中，返回true

$action->objectType = 'product';
r($actionModel->checkIsActionLegal($action, $shadowProducts, $docList, $apiList, $docLibList)) && p() && e('0'); //测试产品属于影子产品，返回false

$action->objectType = 'api';
$action->objectID = 2;
r($actionModel->checkIsActionLegal($action, $shadowProducts, $docList, $apiList, $docLibList)) && p() && e('0'); //测试api文档不在有权限的api文档列表中，返回false

$action->objectType = 'doclib';
r($actionModel->checkIsActionLegal($action, $shadowProducts, $docList, $apiList, $docLibList)) && p() && e('0'); //测试文档库不在有权限的文档库列表中，返回false

$action->objectType = 'bug';

r($actionModel->checkIsActionLegal($action, $shadowProducts, $docList, $apiList, $docLibList)) && p() && e('1'); //测试其他action，返回true