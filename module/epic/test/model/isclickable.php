#!/usr/bin/env php
<?php

/**

title=测试 epicModel::isClickable();
timeout=0
cid=16256

- 步骤1：正常story状态下close动作 @1
- 步骤2：closed状态下close动作 @0
- 步骤3：closed状态下activate动作 @1
- 步骤4：active状态下activate动作 @0
- 步骤5：reviewing状态下recall动作 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/epic.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->title->range('Epic1,Epic2,Epic3,Epic4,Epic5,Epic6,Epic7,Epic8,Epic9,Epic10');
$table->type->range('epic');
$table->status->range('active{3},closed{2},reviewing{2},changing{1},draft{2}');
$table->assignedTo->range('admin,user1,user2');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$epicTest = new epicTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($epicTest->isClickableTest((object)array('status' => 'active', 'type' => 'epic'), 'close')) && p() && e('1'); // 步骤1：正常story状态下close动作
r($epicTest->isClickableTest((object)array('status' => 'closed', 'type' => 'epic'), 'close')) && p() && e('0'); // 步骤2：closed状态下close动作
r($epicTest->isClickableTest((object)array('status' => 'closed', 'type' => 'epic'), 'activate')) && p() && e('1'); // 步骤3：closed状态下activate动作
r($epicTest->isClickableTest((object)array('status' => 'active', 'type' => 'epic'), 'activate')) && p() && e('0'); // 步骤4：active状态下activate动作
r($epicTest->isClickableTest((object)array('status' => 'reviewing', 'type' => 'epic'), 'recall')) && p() && e('1'); // 步骤5：reviewing状态下recall动作