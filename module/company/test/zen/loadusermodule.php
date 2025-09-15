#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadUserModule();
timeout=0
cid=0

- 步骤1：正常用户ID @admin
- 步骤2：用户ID为0 @all
- 步骤3：null用户ID @all
- 步骤4：不存在用户ID @all
- 步骤5：负数用户ID @all

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

// 2. zendata数据准备
$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$table->password->range('123456{10}');
$table->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$table->role->range('admin,dev{4},qa{3},pm{2}');
$table->deleted->range('0{10}');
$table->gen(10);

$companyTable = zenData('company');
$companyTable->id->range('1');
$companyTable->name->range('Test Company');
$companyTable->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$companyTest = new companyTest();

// 5. 测试步骤
r($companyTest->loadUserModuleTest(1)) && p('0') && e('admin'); // 步骤1：正常用户ID
r($companyTest->loadUserModuleTest(0)) && p('0') && e('all'); // 步骤2：用户ID为0
r($companyTest->loadUserModuleTest(null)) && p('0') && e('all'); // 步骤3：null用户ID
r($companyTest->loadUserModuleTest(999)) && p('0') && e('all'); // 步骤4：不存在用户ID
r($companyTest->loadUserModuleTest(-1)) && p('0') && e('all'); // 步骤5：负数用户ID