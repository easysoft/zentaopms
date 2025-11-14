#!/usr/bin/env php
<?php

/**

title=测试 userModel::initViewObjects();
timeout=0
cid=19644

- 步骤1：正常初始化，返回7个元素的数组 @7
- 步骤2：强制刷新，返回7个元素的数组 @7
- 步骤3：检查products为数组类型 @1
- 步骤4：检查projects为数组类型 @1
- 步骤5：检查teams为数组类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{2}');
$product->status->range('normal{4},closed{1}');
$product->acl->range('open{2},private{3}');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5');
$project->type->range('project{5},sprint{3},stage{1},kanban{1}');
$project->status->range('wait{3},doing{5},done{2}');
$project->acl->range('open{4},private{6}');
$project->gen(10);

$team = zenData('team');
$team->id->range('1-15');
$team->root->range('1-10');
$team->type->range('project{8},execution{7}');
$team->account->range('admin{3},user1{4},user2{4},user3{4}');
$team->gen(15);

$stakeholder = zenData('stakeholder');
$stakeholder->id->range('1-12');
$stakeholder->objectType->range('program{3},project{3},sprint{3},product{3}');
$stakeholder->objectID->range('1-3:4');
$stakeholder->user->range('admin{3},user1{3},user2{3},user3{3}');
$stakeholder->gen(12);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($userTest->initViewObjectsTest(false))) && p() && e('7'); // 步骤1：正常初始化，返回7个元素的数组
r(count($userTest->initViewObjectsTest(true))) && p() && e('7'); // 步骤2：强制刷新，返回7个元素的数组
r(is_array($userTest->initViewObjectsTest(false)[0])) && p() && e('1'); // 步骤3：检查products为数组类型
r(is_array($userTest->initViewObjectsTest(false)[1])) && p() && e('1'); // 步骤4：检查projects为数组类型
r(is_array($userTest->initViewObjectsTest(false)[4])) && p() && e('1'); // 步骤5：检查teams为数组类型