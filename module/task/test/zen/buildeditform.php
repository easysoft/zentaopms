#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildEditForm();
timeout=0
cid=18901

- 步骤1：正常任务表单构建
 - 属性success @1
 - 属性hasTitle @1
 - 属性hasStories @1
 - 属性hasTasks @1
 - 属性hasUsers @1
- 步骤2：父任务表单构建
 - 属性success @1
 - 属性hasTaskMembers @1
 - 属性hasModules @1
 - 属性hasExecutions @1
- 步骤3：团队任务表单构建
 - 属性success @1
 - 属性hasSyncChildren @1
 - 属性hasChildDateLimit @1
- 步骤4：关联需求任务表单构建
 - 属性success @1
 - 属性hasManageLink @1
 - 属性hasStories @1
- 步骤5：无效任务ID处理属性success @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->project->range('1-3');
$taskTable->execution->range('1-3');
$taskTable->name->range('测试任务1,测试任务2,测试任务3,测试任务4,测试任务5');
$taskTable->status->range('wait{2},doing{3},done{3},pause{1},cancel{1}');
$taskTable->assignedTo->range('admin{3},user1{3},user2{2},closed{2}');
$taskTable->openedBy->range('admin{5},user1{5}');
$taskTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->buildEditFormTest(1)) && p('success,hasTitle,hasStories,hasTasks,hasUsers') && e('1,1,1,1,1'); // 步骤1：正常任务表单构建
r($taskTest->buildEditFormTest(2)) && p('success,hasTaskMembers,hasModules,hasExecutions') && e('1,1,1,1'); // 步骤2：父任务表单构建
r($taskTest->buildEditFormTest(3)) && p('success,hasSyncChildren,hasChildDateLimit') && e('1,1,1'); // 步骤3：团队任务表单构建
r($taskTest->buildEditFormTest(4)) && p('success,hasManageLink,hasStories') && e('1,1,1'); // 步骤4：关联需求任务表单构建
r($taskTest->buildEditFormTest(999)) && p('success') && e('1'); // 步骤5：无效任务ID处理