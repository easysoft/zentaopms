#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoApiSpec();
timeout=0
cid=0

- 执行apiTest模块的createDemoApiSpecTest方法，参数是'16.0', $fullApiMap, $fullModuleMap, 'admin'  @1
- 执行apiTest模块的createDemoApiSpecTest方法，参数是'16.0', $fullApiMap, $fullModuleMap, 'user1'  @1
- 执行apiTest模块的createDemoApiSpecTest方法，参数是'16.0', $fullApiMap, $fullModuleMap, 'test'  @1
- 执行apiTest模块的createDemoApiSpecTest方法，参数是'16.0', $fullApiMap, $fullModuleMap, 'user2'  @1
- 执行apiTest模块的createDemoApiSpecTest方法，参数是'16.0', $fullApiMap, $fullModuleMap, 'guest'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

$apiTable = zenData('api');
$apiTable->id->range('1-10');
$apiTable->lib->range('1-5');
$apiTable->module->range('1001-1010');
$apiTable->title->range('用户接口,产品接口,项目接口,任务接口,缺陷接口');
$apiTable->path->range('/api/user,/api/product,/api/project,/api/task,/api/bug');
$apiTable->method->range('GET{2},POST{2},PUT{1}');
$apiTable->requestType->range('application/json');
$apiTable->responseType->range('application/json');
$apiTable->status->range('doing{2},done{3}');
$apiTable->owner->range('admin,user1,user2');
$apiTable->addedBy->range('admin');
$apiTable->addedDate->range('`2023-01-01 10:00:00`');
$apiTable->gen(10);

$moduleTable = zenData('module');
$moduleTable->id->range('1001-1010');
$moduleTable->root->range('1-5');
$moduleTable->type->range('api{10}');
$moduleTable->name->range('用户模块,产品模块,项目模块,任务模块,缺陷模块,系统模块,管理模块,报表模块,设置模块,接口模块');
$moduleTable->path->range(',1001,{1},1002,{1},1003,{1},1004,{1},1005,{1},1006,{1},1007,{1},1008,{1},1009,{1},1010,{1}');
$moduleTable->grade->range('1{10}');
$moduleTable->order->range('1-10');
$moduleTable->deleted->range('0{10}');
$moduleTable->gen(10);

su('admin');

$apiTest = new apiTest();

$fullApiMap = array();
for($i = 0; $i <= 500; $i++) {
    $fullApiMap[$i] = max(1, $i);
}
$fullModuleMap = array();
for($i = 0; $i <= 5000; $i++) {
    $fullModuleMap[$i] = max(1, $i);
}

r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'admin')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'user1')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'test')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'user2')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'guest')) && p() && e(1);