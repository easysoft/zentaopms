#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoApiSpec();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：边界值 @1
- 步骤3：不同用户 @1
- 步骤4：不同版本 @1
- 步骤5：部分数据 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$apiSpecTable = zenData('api_spec');
$apiSpecTable->doc->range('1-10');
$apiSpecTable->title->range('用户接口规格,产品接口规格,项目接口规格,任务接口规格,缺陷接口规格,测试接口规格,订单接口规格,支付接口规格,通知接口规格,系统接口规格');
$apiSpecTable->path->range('/api/user,/api/product,/api/project,/api/task,/api/bug,/api/test,/api/order,/api/pay,/api/notify,/api/system');
$apiSpecTable->module->range('1001-1010');
$apiSpecTable->protocol->range('HTTP{8},HTTPS{2}');
$apiSpecTable->method->range('GET{4},POST{4},PUT{1},DELETE{1}');
$apiSpecTable->requestType->range('application/json{8},application/xml{2}');
$apiSpecTable->responseType->range('application/json{8},application/xml{2}');
$apiSpecTable->status->range('doing{2},done{6},hidden{2}');
$apiSpecTable->owner->range('admin{3},user1{3},user2{2},test{2}');
$apiSpecTable->version->range('1-3');
$apiSpecTable->addedBy->range('admin{5},user1{3},test{2}');
$apiSpecTable->addedDate->range('`2023-01-01 10:00:00`-`2023-12-31 23:59:59`');
$apiSpecTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10, 2 => 20, 3 => 30), array(1001 => 2001, 1002 => 2002, 1003 => 2003), 'admin')) && p() && e(1);  // 步骤1：正常情况
r($apiTest->createDemoApiSpecTest('16.0', array(), array(), 'admin')) && p() && e(1);  // 步骤2：边界值
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10, 2 => 20), array(1001 => 2001, 1002 => 2002), 'user1')) && p() && e(1);  // 步骤3：不同用户
r($apiTest->createDemoApiSpecTest('15.5', array(1 => 10, 2 => 20), array(1001 => 2001, 1002 => 2002), 'admin')) && p() && e(1);  // 步骤4：不同版本
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10), array(1001 => 2001), 'test')) && p() && e(1);  // 步骤5：部分数据