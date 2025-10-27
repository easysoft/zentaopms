#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignEditData();
timeout=0
cid=0

- 步骤1：正常版本编辑数据生成属性title @Build1 - 编辑版本
- 步骤2：产品信息正确生成第products条的2属性 @Product2
- 步骤3：多分支产品版本编辑数据生成第branchTagOption条的1属性 @Branch1
- 步骤4：不存在的产品版本编辑数据生成属性product @
- 步骤5：用户数量验证属性users @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$build = zenData('build');
$build->id->range('1-5');
$build->name->range('Build{1-5}');
$build->product->range('1-3:2R');
$build->branch->range('0{3},1{2}');
$build->project->range('1-2:3R');
$build->execution->range('1-2:3R');
$build->builder->range('admin{3},user1{2}');
$build->gen(5);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('Product{1-3}');
$product->type->range('normal{2},branch{1}');
$product->status->range('normal{3}');
$product->gen(3);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user{1-4}');
$user->realname->range('Admin,User{1-4}');
$user->deleted->range('0{5}');
$user->gen(5);

$project = zenData('project');
$project->id->range('1-2');
$project->name->range('Project{1-2}');
$project->status->range('wait{1},doing{1}');
$project->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$buildTest = new buildTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($buildTest->assignEditDataTest((object)array('id' => 1, 'name' => 'Build1', 'product' => 1, 'branch' => '0', 'execution' => 1, 'project' => 1, 'builder' => 'admin', 'builds' => '', 'system' => 1))) && p('title') && e('Build1 - 编辑版本'); // 步骤1：正常版本编辑数据生成
r($buildTest->assignEditDataTest((object)array('id' => 2, 'name' => 'Build2', 'product' => 2, 'branch' => '0', 'execution' => 2, 'project' => 2, 'builder' => 'user1', 'builds' => '', 'system' => 2))) && p('products:2') && e('Product2'); // 步骤2：产品信息正确生成
r($buildTest->assignEditDataTest((object)array('id' => 3, 'name' => 'Build3', 'product' => 3, 'branch' => '1,2', 'execution' => 1, 'project' => 1, 'builder' => 'admin', 'builds' => '', 'system' => 1))) && p('branchTagOption:1') && e('Branch1'); // 步骤3：多分支产品版本编辑数据生成
r($buildTest->assignEditDataTest((object)array('id' => 4, 'name' => 'Build4', 'product' => 99, 'branch' => '0', 'execution' => 1, 'project' => 1, 'builder' => 'admin', 'builds' => '', 'system' => 1))) && p('product') && e(''); // 步骤4：不存在的产品版本编辑数据生成
r($buildTest->assignEditDataTest((object)array('id' => 5, 'name' => 'Build5', 'product' => 1, 'branch' => '0', 'execution' => 1, 'project' => 1, 'builder' => 'user1', 'builds' => '', 'system' => 1))) && p('users') && e('1'); // 步骤5：用户数量验证