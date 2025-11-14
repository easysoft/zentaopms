#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoStruct();
timeout=0
cid=15097

- 步骤1：正常的libID创建演示数据结构 @1
- 步骤2：无效libID创建演示数据结构 @1
- 步骤3：使用libID=2创建演示数据结构 @1
- 步骤4：不同用户账户创建演示数据结构 @1
- 步骤5：使用test用户创建演示数据结构 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$doclib = zenData('doclib');
$doclib->id->range('1-10');
$doclib->type->range('api');
$doclib->name->range('测试API库1,测试API库2,测试API库3');
$doclib->baseUrl->range('http://test1.com,http://test2.com,http://test3.com');
$doclib->acl->range('open');
$doclib->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($apiTest->createDemoStructTest(1, '16.0', 'admin')) && p() && e('1'); // 步骤1：正常的libID创建演示数据结构
r($apiTest->createDemoStructTest(0, '16.0', 'admin')) && p() && e('1'); // 步骤2：无效libID创建演示数据结构
r($apiTest->createDemoStructTest(2, '16.0', 'admin')) && p() && e('1'); // 步骤3：使用libID=2创建演示数据结构
r($apiTest->createDemoStructTest(3, '16.0', 'user')) && p() && e('1'); // 步骤4：不同用户账户创建演示数据结构
r($apiTest->createDemoStructTest(1, '16.0', 'test')) && p() && e('1'); // 步骤5：使用test用户创建演示数据结构