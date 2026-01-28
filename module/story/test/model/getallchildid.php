#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getAllChildId();
timeout=0
cid=18498

- 步骤1：获取父需求(ID=1)包含自己的所有子需求数量 @5
- 步骤2：获取父需求(ID=1)不包含自己的所有子需求数量 @4
- 步骤3：获取不存在需求的子需求数量 @0
- 步骤4：测试传入0作为storyID参数的结果数量 @0
- 步骤5：验证包含自己时结果中包含自己的ID @1
- 步骤6：验证不包含自己时结果中不包含自己的ID @1
- 步骤7：获取叶子需求(ID=3)的所有子需求数量 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->parent->range('0,1,1,2,2,0,0,0,0,0');
$table->isParent->range('1,1,0,1,0,0,0,0,0,0');
$table->root->range('1,1,1,1,1,6,7,8,9,10');
$table->path->range("',1,',',1,2,',',1,3,',',1,2,4,',',1,2,5,',',6,',',7,',',8,',',9,',',10,'");
$table->product->range('1');
$table->type->range('story');
$table->status->range('active');
$table->deleted->range('0');
$table->title->prefix('需求');
$table->grade->range('1,2,3,3,3,1,1,1,1,1');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($storyTest->getAllChildIdTest(1, true, false))) && p() && e('5'); // 步骤1：获取父需求(ID=1)包含自己的所有子需求数量
r(count($storyTest->getAllChildIdTest(1, false, false))) && p() && e('4'); // 步骤2：获取父需求(ID=1)不包含自己的所有子需求数量
r(count($storyTest->getAllChildIdTest(999, true, false))) && p() && e('0'); // 步骤3：获取不存在需求的子需求数量
r(count($storyTest->getAllChildIdTest(0, true, false))) && p() && e('0'); // 步骤4：测试传入0作为storyID参数的结果数量
r(in_array('1', $storyTest->getAllChildIdTest(1, true, false))) && p() && e('1'); // 步骤5：验证包含自己时结果中包含自己的ID
r(!in_array('1', $storyTest->getAllChildIdTest(1, false, false))) && p() && e('1'); // 步骤6：验证不包含自己时结果中不包含自己的ID
r(count($storyTest->getAllChildIdTest(3, true, false))) && p() && e('5'); // 步骤7：获取叶子需求(ID=3)的所有子需求数量