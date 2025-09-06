#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoApiSpec();
cid=0

- 测试步骤1：正常版本16.0和完整apiMap、moduleMap以及admin用户 >> 期望返回true表示创建成功
- 测试步骤2：不同用户user1创建demo API规格 >> 期望返回true表示创建成功
- 测试步骤3：空的apiMap和moduleMap测试边界情况 >> 期望返回true但无数据插入
- 测试步骤4：无效版本参数测试异常处理 >> 期望正常处理无异常
- 测试步骤5：普通用户权限创建demo API规格 >> 期望返回true表示创建成功

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

$apiSpecTable = zenData('api_spec');
$apiSpecTable->doc->range('1-20');
$apiSpecTable->title->range('用户接口规格,产品接口规格,项目接口规格,任务接口规格,缺陷接口规格');
$apiSpecTable->path->range('/api/user,/api/product,/api/project,/api/task,/api/bug');
$apiSpecTable->module->range('1001-1020');
$apiSpecTable->protocol->range('HTTP{15},HTTPS{5}');
$apiSpecTable->method->range('GET{5},POST{5},PUT{3},DELETE{2}');
$apiSpecTable->requestType->range('application/json{15},application/xml{5}');
$apiSpecTable->responseType->range('application/json{15},application/xml{5}');
$apiSpecTable->status->range('doing{5},done{10},hidden{5}');
$apiSpecTable->owner->range('admin{5},user1{5},user2{5},test{5}');
$apiSpecTable->version->range('1-3');
$apiSpecTable->addedBy->range('admin{10},user1{5},test{5}');
$apiSpecTable->addedDate->range('`2023-01-01 10:00:00`-`2023-12-31 23:59:59`');
$apiSpecTable->gen(20);

su('admin');

$apiTest = new apiTest();

$fullApiMap = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);
$fullModuleMap = array(1001 => 1001, 1002 => 1002, 1003 => 1003, 1004 => 1004, 1005 => 1005);
$emptyApiMap = array();
$emptyModuleMap = array();

r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'admin')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'user1')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('16.0', $emptyApiMap, $emptyModuleMap, 'admin')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('15.5', $fullApiMap, $fullModuleMap, 'admin')) && p() && e(1);
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'test')) && p() && e(1);