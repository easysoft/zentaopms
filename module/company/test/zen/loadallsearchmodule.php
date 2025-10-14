#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadAllSearchModule();
timeout=0
cid=0

- 步骤1：有效用户ID和查询ID @admin
- 步骤2：用户ID为0的情况 @all
- 步骤3：空字符串查询ID @user1
- 步骤4：数字字符串查询ID @user2
- 步骤5：不存在的用户ID @all

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,test1,test2,test3,test4,test5');
$userTable->password->range('123456{10}');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,测试1,测试2,测试3,测试4,测试5');
$userTable->role->range('admin{1},dev{5},qa{2},pm{2}');
$userTable->dept->range('1-5');
$userTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->code->range('prod1,prod2,prod3,prod4,prod5');
$productTable->status->range('normal{5}');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->code->range('proj1,proj2,proj3,proj4,proj5');
$projectTable->type->range('project{5}');
$projectTable->status->range('wait{2},doing{2},done{1}');
$projectTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('11-15');
$executionTable->name->range('执行1,执行2,执行3,执行4,执行5');
$executionTable->code->range('exec1,exec2,exec3,exec4,exec5');
$executionTable->type->range('execution{5}');
$executionTable->parent->range('1-5');
$executionTable->project->range('1-5');
$executionTable->status->range('wait{2},doing{2},done{1}');
$executionTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$companyTest = new companyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($companyTest->loadAllSearchModuleTest(1, 1)) && p() && e('admin'); // 步骤1：有效用户ID和查询ID
r($companyTest->loadAllSearchModuleTest(0, 2)) && p() && e('all'); // 步骤2：用户ID为0的情况
r($companyTest->loadAllSearchModuleTest(2, '')) && p() && e('user1'); // 步骤3：空字符串查询ID
r($companyTest->loadAllSearchModuleTest(3, '5')) && p() && e('user2'); // 步骤4：数字字符串查询ID
r($companyTest->loadAllSearchModuleTest(999, 999)) && p() && e('all'); // 步骤5：不存在的用户ID