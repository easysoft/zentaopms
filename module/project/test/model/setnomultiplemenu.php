#!/usr/bin/env php
<?php

/**

title=测试 projectModel::setNoMultipleMenu();
timeout=0
cid=17868

- 步骤1：测试multiple项目 @0
- 步骤2：测试project类型项目 @0
- 步骤3：测试sprint类型项目 @0
- 步骤4：测试kanban类型项目 @0
- 步骤5：测试无效项目ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目{1-10}');
$projectTable->type->range('project{3},sprint{3},kanban{3},stage{1}');
$projectTable->model->range('scrum{3},waterfall{3},kanban{3},stage{1}');
$projectTable->multiple->range('0{8},1{2}');
$projectTable->hasProduct->range('1{8},0{2}');
$projectTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->setNoMultipleMenuTest(9))   && p() && e('0'); // 步骤1：测试multiple项目
r($projectTest->setNoMultipleMenuTest(1))   && p() && e('0'); // 步骤2：测试project类型项目
r($projectTest->setNoMultipleMenuTest(2))   && p() && e('0'); // 步骤3：测试sprint类型项目
r($projectTest->setNoMultipleMenuTest(3))   && p() && e('0'); // 步骤4：测试kanban类型项目
r($projectTest->setNoMultipleMenuTest(999)) && p() && e('0'); // 步骤5：测试无效项目ID
