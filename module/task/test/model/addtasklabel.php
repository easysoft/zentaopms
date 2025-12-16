#!/usr/bin/env php
<?php

/**

title=测试 taskModel::addTaskLabel();
timeout=0
cid=18757

- 步骤1：空数组情况 @0
- 步骤2：普通任务第0条的value属性 @1
- 步骤3：父任务情况第0条的value属性 @4
- 步骤4：子任务情况第0条的value属性 @6
- 步骤5：混合任务情况（返回结果数量） @3
- 步骤6：不存在任务ID情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. zendata数据准备
$table = zenData('task');
$table->id->range('1-10');
$table->name->range('任务{1-10}');
$table->isParent->range('0{3},1{2},0{5}');
$table->parent->range('0{5},4,4,0{3}');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskTest();

// 5. 测试步骤：必须包含至少5个测试步骤
r(count($taskTest->addTaskLabelTest(array()))) && p() && e('0'); // 步骤1：空数组情况
r($taskTest->addTaskLabelTest(array(1 => 1))) && p('0:value') && e('1'); // 步骤2：普通任务
r($taskTest->addTaskLabelTest(array(4 => 4))) && p('0:value') && e('4'); // 步骤3：父任务情况
r($taskTest->addTaskLabelTest(array(6 => 6))) && p('0:value') && e('6'); // 步骤4：子任务情况
r(count($taskTest->addTaskLabelTest(array(1 => 1, 4 => 4, 6 => 6)))) && p() && e('3'); // 步骤5：混合任务情况（返回结果数量）
r(count($taskTest->addTaskLabelTest(array(999 => 999)))) && p() && e('0'); // 步骤6：不存在任务ID情况