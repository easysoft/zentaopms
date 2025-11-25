#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignKanbanVars();
timeout=0
cid=16403

- 执行executionZenTest模块的assignKanbanVarsTest方法，参数是1 
 - 第users条的admin属性 @管理员
 - 属性productNum @2
- 执行executionZenTest模块的assignKanbanVarsTest方法，参数是999 属性productNum @0
- 执行executionZenTest模块的assignKanbanVarsTest方法，参数是1 第users条的admin属性 @管理员
- 执行executionZenTest模块的assignKanbanVarsTest方法，参数是1 属性productID @1
- 执行executionZenTest模块的assignKanbanVarsTest方法，参数是1 属性isLimited @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 准备测试数据
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户一,用户二,用户三,用户四');
$userTable->password->range('123456{5}');
$userTable->role->range('admin,qa,dev,pm,po');
$userTable->deleted->range('0{5}');
$userTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('1-3');
$executionTable->name->range('执行1,执行2,执行3');
$executionTable->type->range('sprint{3}');
$executionTable->status->range('doing{2},closed');
$executionTable->deleted->range('0{3}');
$executionTable->gen(3);

$productTable = zenData('product');
$productTable->id->range('1-2');
$productTable->name->range('产品一,产品二');
$productTable->status->range('normal{2}');
$productTable->deleted->range('0{2}');
$productTable->gen(2);

$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('1{2},2{1}');
$projectProductTable->product->range('1,2,1');
$projectProductTable->gen(3);

$branchTable = zenData('branch');
$branchTable->id->range('1-2');
$branchTable->product->range('1{2}');
$branchTable->name->range('分支一,分支二');
$branchTable->status->range('active{2}');
$branchTable->deleted->range('0{2}');
$branchTable->gen(2);

$planTable = zenData('productplan');
$planTable->id->range('1-3');
$planTable->product->range('1{2},2');
$planTable->title->range('计划一,计划二,计划三');
$planTable->deleted->range('0{3}');
$planTable->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$executionZenTest = new executionZenTest();

// 测试步骤1：正常执行ID调用方法
r($executionZenTest->assignKanbanVarsTest(1)) && p('users:admin;productNum') && e('管理员;2');

// 测试步骤2：无效执行ID调用方法
r($executionZenTest->assignKanbanVarsTest(999)) && p('productNum') && e('0');

// 测试步骤3：测试用户数据获取
r($executionZenTest->assignKanbanVarsTest(1)) && p('users:admin') && e('管理员');

// 测试步骤4：测试产品关联数据获取
r($executionZenTest->assignKanbanVarsTest(1)) && p('productID') && e('1');

// 测试步骤5：测试权限限制变量获取  
r($executionZenTest->assignKanbanVarsTest(1)) && p('isLimited') && e('~~');