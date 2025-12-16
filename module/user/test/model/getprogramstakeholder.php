#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProgramStakeholder();
timeout=0
cid=19627

- 步骤1：正常情况，检查产品1有admin第1条的admin属性 @admin
- 步骤2：空数组 @0
- 步骤3：不存在项目ID @0
- 步骤4：仅项目经理第5条的pm4属性 @pm4
- 步骤5：单项目多产品
 - 第1条的admin属性 @admin
 - 第2条的admin属性 @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备
$stakeholderTable = zenData('stakeholder');
$stakeholderTable->objectID->range('1,2,3,3,4,4,4,4');
$stakeholderTable->objectType->range('program');
$stakeholderTable->user->range('admin,user1,user2,user3,user4,user5,admin,user1');
$stakeholderTable->gen(8);

$projectTable = zenData('project');
$projectTable->id->range('1-6');
$projectTable->type->range('program');
$projectTable->PM->range('admin,pm1,pm2,pm3,pm4,pm5');
$projectTable->gen(6);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$userTest = new userTest();

// 5. 测试步骤（必须包含至少5个）
r($userTest->getProgramStakeholderTest(array(1 => array(1, 2), 2 => array(3)))) && p('1:admin') && e('admin'); // 步骤1：正常情况，检查产品1有admin
r($userTest->getProgramStakeholderTest(array())) && p() && e(0); // 步骤2：空数组
r($userTest->getProgramStakeholderTest(array(999 => array()))) && p() && e(0); // 步骤3：不存在项目ID
r($userTest->getProgramStakeholderTest(array(5 => array(5)))) && p('5:pm4') && e('pm4'); // 步骤4：仅项目经理
r($userTest->getProgramStakeholderTest(array(1 => array(1, 2)))) && p('1:admin;2:admin') && e('admin;admin'); // 步骤5：单项目多产品