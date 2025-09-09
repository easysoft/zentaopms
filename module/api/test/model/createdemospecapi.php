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
- 步骤6：异常版本号 @1
- 步骤7：复杂映射关系 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$apiSpecTable = zenData('api_spec');
$apiSpecTable->doc->range('1-20');
$apiSpecTable->title->range('用户接口规格,产品接口规格,项目接口规格,任务接口规格,缺陷接口规格,测试接口规格,订单接口规格,支付接口规格,通知接口规格,系统接口规格,报表接口规格,文档接口规格,权限接口规格,配置接口规格,日志接口规格,消息接口规格,统计接口规格,搜索接口规格,导出接口规格,备份接口规格');
$apiSpecTable->path->range('/api/user,/api/product,/api/project,/api/task,/api/bug,/api/test,/api/order,/api/pay,/api/notify,/api/system,/api/report,/api/doc,/api/priv,/api/config,/api/log,/api/message,/api/stat,/api/search,/api/export,/api/backup');
$apiSpecTable->module->range('1001-1020');
$apiSpecTable->protocol->range('HTTP{12},HTTPS{8}');
$apiSpecTable->method->range('GET{8},POST{8},PUT{2},DELETE{2}');
$apiSpecTable->requestType->range('application/json{15},application/xml{3},multipart/form-data{2}');
$apiSpecTable->responseType->range('application/json{15},application/xml{3},text/plain{2}');
$apiSpecTable->status->range('doing{4},done{12},hidden{4}');
$apiSpecTable->owner->range('admin{5},user1{5},user2{4},test{3},manager{3}');
$apiSpecTable->version->range('1-5');
$apiSpecTable->addedBy->range('admin{8},user1{6},test{3},manager{3}');
$apiSpecTable->addedDate->range('`2023-01-01 10:00:00`-`2023-12-31 23:59:59`');
$apiSpecTable->gen(20);

// 准备api表数据
$apiTable = zenData('api');
$apiTable->title->range('用户管理接口,产品管理接口,项目管理接口,任务管理接口,缺陷管理接口');
$apiTable->lib->range('1-5');
$apiTable->module->range('1001-1005');
$apiTable->path->range('/api/user,/api/product,/api/project,/api/task,/api/bug');
$apiTable->method->range('GET{2},POST{2},PUT{1}');
$apiTable->status->range('doing{1},done{3},hidden{1}');
$apiTable->version->range('1-3');
$apiTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10, 2 => 20, 3 => 30), array(1001 => 2001, 1002 => 2002, 1003 => 2003), 'admin')) && p() && e(1); // 步骤1：正常情况
r($apiTest->createDemoApiSpecTest('16.0', array(), array(), 'admin')) && p() && e(1); // 步骤2：边界值
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10, 2 => 20), array(1001 => 2001, 1002 => 2002), 'user1')) && p() && e(1); // 步骤3：不同用户
r($apiTest->createDemoApiSpecTest('15.5', array(1 => 10, 2 => 20), array(1001 => 2001, 1002 => 2002), 'admin')) && p() && e(1); // 步骤4：不同版本
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10), array(1001 => 2001), 'test')) && p() && e(1); // 步骤5：部分数据
r($apiTest->createDemoApiSpecTest('18.0', array(5 => 50, 6 => 60), array(2001 => 3001, 2002 => 3002), 'manager')) && p() && e(1); // 步骤6：异常版本号
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 100, 2 => 200, 3 => 300, 4 => 400), array(1001 => 5001, 1002 => 5002, 1003 => 5003, 1004 => 5004), 'admin')) && p() && e(1); // 步骤7：复杂映射关系