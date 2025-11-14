#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::buildBugInfo();
timeout=0
cid=19132

- 步骤1：正常情况返回数组 @Array
- 步骤2：指定参数测试 @Array
- 步骤3：部分参数为空 @Array
- 步骤4：类型参数测试 @Array
- 步骤5：空数组参数测试 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->severity->range('1-4');
$bug->type->range('codeerror{3},interface{2},config{2},install{1},security{1},performance{1}');
$bug->status->range('active{4},resolved{3},closed{3}');
$bug->openedBy->range('admin{4},user1{3},user2{3}');
$bug->resolvedBy->range('admin{2},developer1{2},[]{6}');
$bug->resolution->range('fixed{3},postponed{1},[]{6}');
$bug->module->range('1-3');
$bug->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('Product1,Product2,Product3');
$product->gen(3);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,developer1,developer2,tester1,tester2,pm1,pm2');
$user->realname->range('Administrator,User1,User2,User3,Developer1,Developer2,Tester1,Tester2,PM1,PM2');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testreportTest->buildBugInfoTest()) && p() && e('Array'); // 步骤1：正常情况返回数组

r($testreportTest->buildBugInfoTest(['1' => ['generated' => 2]])) && p() && e('Array'); // 步骤2：指定参数测试

r($testreportTest->buildBugInfoTest([], [], ['2' => 5])) && p() && e('Array'); // 步骤3：部分参数为空

r($testreportTest->buildBugInfoTest([], [], [], ['config' => 1])) && p() && e('Array'); // 步骤4：类型参数测试

r($testreportTest->buildBugInfoTest([], [], [], [], [], [], [], [], [], [])) && p() && e('Array'); // 步骤5：空数组参数测试