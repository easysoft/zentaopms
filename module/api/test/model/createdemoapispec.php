#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoApiSpec();
cid=0

- 测试步骤1：正常参数创建演示API规范 >> 期望返回1
- 测试步骤2：空的apiMap和moduleMap处理 >> 期望返回1  
- 测试步骤3：不同用户账号创建 >> 期望返回1
- 测试步骤4：不同版本号处理 >> 期望返回1
- 测试步骤5：复杂映射关系处理 >> 期望返回1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$apiSpecTable = zenData('apispec');
$apiSpecTable->doc->range('1-20');
$apiSpecTable->title->range('用户接口规格,产品接口规格,项目接口规格,任务接口规格,缺陷接口规格');
$apiSpecTable->path->range('/api/user,/api/product,/api/project,/api/task,/api/bug');
$apiSpecTable->module->range('1001-1005');
$apiSpecTable->protocol->range('HTTP{4},HTTPS{1}');
$apiSpecTable->method->range('GET{3},POST{2}');
$apiSpecTable->requestType->range('application/json');
$apiSpecTable->responseType->range('application/json');
$apiSpecTable->status->range('done{4},doing{1}');
$apiSpecTable->owner->range('admin{3},user1{2}');
$apiSpecTable->version->range('1-3');
$apiSpecTable->addedBy->range('admin');
$apiSpecTable->addedDate->range('`2023-01-01 10:00:00`');
$apiSpecTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10, 2 => 20), array(1001 => 2001, 1002 => 2002), 'admin')) && p() && e(1); // 步骤1：正常情况
r($apiTest->createDemoApiSpecTest('16.0', array(), array(), 'admin')) && p() && e(1); // 步骤2：空的映射关系
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 10), array(1001 => 2001), 'user1')) && p() && e(1); // 步骤3：不同用户
r($apiTest->createDemoApiSpecTest('15.5', array(1 => 10, 2 => 20), array(1001 => 2001, 1002 => 2002), 'admin')) && p() && e(1); // 步骤4：不同版本
r($apiTest->createDemoApiSpecTest('16.0', array(1 => 100, 2 => 200), array(1001 => 5001, 1002 => 5002), 'manager')) && p() && e(1); // 步骤5：复杂映射关系