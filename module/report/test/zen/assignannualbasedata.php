#!/usr/bin/env php
<?php

/**

title=测试 reportZen::assignAnnualBaseData();
timeout=0
cid=0

- 步骤1：正常用户和部门输入
 - 属性success @yes
 - 属性account @admin
 - 属性dept @1
 - 属性year @2024
- 步骤2：空参数输入情况
 - 属性hasYears @yes
 - 属性hasAccounts @yes
 - 属性hasDept @yes
 - 属性hasYear @yes
- 步骤3：无效用户输入情况
 - 属性success @yes
 - 属性dept @2
 - 属性year @2023
- 步骤4：特定年份输入情况
 - 属性success @yes
 - 属性dept @2
 - 属性year @2022
- 步骤5：部门为0的边界情况
 - 属性deptZero @yes
 - 属性accountsEmpty @yes
 - 属性year @2024

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->dept->range('1,2,2,3,3');
$userTable->deleted->range('0{5}');
$userTable->gen(5);

$deptTable = zenData('dept');
$deptTable->id->range('1-3');
$deptTable->name->range('开发部,测试部,产品部');
$deptTable->parent->range('0,0,0');
$deptTable->path->range(',1,,2,,3,');
$deptTable->gen(3);

$actionTable = zenData('action');
$actionTable->id->range('1-20');
$actionTable->actor->range('admin{4},user1{4},user2{4},user3{4},user4{4}');
$actionTable->date->range('`2020-01-01 00:00:00`,`2021-06-01 12:30:00`,`2022-12-01 23:59:59`,`2023-06-01 10:15:30`,`2024-01-01 14:20:45`');
$actionTable->objectType->range('story{4},task{4},bug{4},case{4},product{4}');
$actionTable->objectID->range('1-10');
$actionTable->action->range('opened{4},edited{4},closed{4},assigned{4},created{4}');
$actionTable->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$reportTest = new reportTest();

// 5. 必须包含至少5个测试步骤
r($reportTest->assignAnnualBaseDataTest('admin', '1', '2024')) && p('success,account,dept,year') && e('yes,admin,1,2024'); // 步骤1：正常用户和部门输入
r($reportTest->assignAnnualBaseDataTest('', '', '')) && p('hasYears,hasAccounts,hasDept,hasYear') && e('yes,yes,yes,yes'); // 步骤2：空参数输入情况
r($reportTest->assignAnnualBaseDataTest('invaliduser', '2', '2023')) && p('success,dept,year') && e('yes,2,2023'); // 步骤3：无效用户输入情况
r($reportTest->assignAnnualBaseDataTest('user1', '2', '2022')) && p('success,dept,year') && e('yes,2,2022'); // 步骤4：特定年份输入情况
r($reportTest->assignAnnualBaseDataTest('', '0', '2024')) && p('deptZero,accountsEmpty,year') && e('yes,yes,2024'); // 步骤5：部门为0的边界情况