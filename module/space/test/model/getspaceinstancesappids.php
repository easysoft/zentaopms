#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSpaceInstancesAppIDs();
timeout=0
cid=18397

- 步骤1：正常情况 - 空间1有3个应用 @3
- 步骤2：不存在的空间ID @0
- 步骤3：空间ID为0时查询所有应用 @10
- 步骤4：验证空间1第一个应用的appID第1条的appID属性 @101
- 步骤5：空间2有2个应用 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/space.unittest.class.php';

// 2. zendata数据准备
$space = zenData('space');
$space->id->range('1-5');
$space->name->range('TestSpace1,TestSpace2,TestSpace3,TestSpace4,TestSpace5');
$space->owner->range('admin{5}');
$space->deleted->range('0{5}');
$space->gen(5);

$instance = zenData('instance');
$instance->id->range('1-10');
$instance->space->range('1{3},2{2},3{3},4{1},5{1}');
$instance->appID->range('101-110');
$instance->name->range('App1,App2,App3,App4,App5,App6,App7,App8,App9,App10');
$instance->deleted->range('0{10}');
$instance->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$spaceTest = new spaceTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($spaceTest->getSpaceInstancesAppIDsTest(1))) && p() && e('3'); // 步骤1：正常情况 - 空间1有3个应用
r(count($spaceTest->getSpaceInstancesAppIDsTest(999))) && p() && e('0'); // 步骤2：不存在的空间ID
r(count($spaceTest->getSpaceInstancesAppIDsTest(0))) && p() && e('10'); // 步骤3：空间ID为0时查询所有应用
r($spaceTest->getSpaceInstancesAppIDsTest(1)) && p('1:appID') && e('101'); // 步骤4：验证空间1第一个应用的appID
r(count($spaceTest->getSpaceInstancesAppIDsTest(2))) && p() && e('2'); // 步骤5：空间2有2个应用