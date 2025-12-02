#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(5);

su('admin');

/**

title=测试 actionModel->processExecutionAndProjectActionExtra();
timeout=0
cid=14923

- 检查只有1个产品 @1
- 检查extra中是否有id=1的产品 @1
- 检查extra中是否有id=2的产品 @1
- 没有extra属性extra @~~
- vision 为lite属性extra @~~

*/

global $tester, $config;
$actionModel = $tester->loadModel('action');

$action = new stdclass();
$action->objectType = 'execution';
$action->objectID   = '3';
$config->vision     = 'rnd';
$action->extra      = '1';

$actionModel->processExecutionAndProjectActionExtra($action);
r(strpos($action->extra, '#1 正常产品1') !== false) && p() && e('1');   //检查只有1个产品

$action->extra = '1,2';
$actionModel->processExecutionAndProjectActionExtra($action);
r(strpos($action->extra, '#1 正常产品1') !== false) && p() && e('1');   //检查extra中是否有id=1的产品
r(strpos($action->extra, '#2 正常产品2') !== false) && p() && e('1');   //检查extra中是否有id=2的产品

$action->extra = '';
$actionModel->processExecutionAndProjectActionExtra($action);
r($action) && p('extra') && e('~~'); //没有extra

$config->vision = 'lite';
$action->extra = '1,2';
$actionModel->processExecutionAndProjectActionExtra($action);
r($action) && p('extra') && e('~~'); //vision 为lite
