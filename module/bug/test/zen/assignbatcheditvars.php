#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignBatchEditVars();
timeout=0
cid=0

- 步骤1：正常情况指定产品ID和分支
 - 属性productID @1
 - 属性branch @main
- 步骤2：空产品ID从bugs获取产品列表
 - 属性productID @0
 - 属性productIdList @2
- 步骤3：无效产品ID边界情况
 - 属性productID @999
 - 属性branch @invalid
- 步骤4：空分支参数
 - 属性productID @1
 - 属性branch @~~
- 步骤5：验证视图数据完整性
 - 属性title @产品2-BUG批量编辑
 - 属性customFields @7
 - 属性bugs @3
 - 属性users @6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-2');
$bug->branch->range('0{3},1{3},2{4}');
$bug->title->range('Bug测试标题1,Bug测试标题2,Bug测试标题3,Bug测试标题4,Bug测试标题5');
$bug->status->range('active{5},resolved{3},closed{2}');
$bug->openedBy->range('admin{5},user1{3},user2{2}');
$bug->assignedTo->range('admin{3},user1{4},user2{3}');
$bug->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->type->range('normal{2},branch{1}');
$product->shadow->range('0{2},1{1}');
$product->gen(3);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{8},1{2}');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->assignBatchEditVarsTest(1, 'main')) && p('productID,branch') && e('1,main'); // 步骤1：正常情况指定产品ID和分支
r($bugTest->assignBatchEditVarsTest(0, '')) && p('productID,productIdList') && e('0,2'); // 步骤2：空产品ID从bugs获取产品列表
r($bugTest->assignBatchEditVarsTest(999, 'invalid')) && p('productID,branch') && e('999,invalid'); // 步骤3：无效产品ID边界情况
r($bugTest->assignBatchEditVarsTest(1, '')) && p('productID,branch') && e('1,~~'); // 步骤4：空分支参数
r($bugTest->assignBatchEditVarsTest(2, 'test')) && p('title,customFields,bugs,users') && e('产品2-BUG批量编辑,7,3,6'); // 步骤5：验证视图数据完整性