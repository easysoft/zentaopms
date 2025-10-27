#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTasksForBatchCreate();
timeout=0
cid=0

- 步骤1：正常批量创建任务数据构建属性count(*) @3
- 步骤2：父任务名称为空时的验证 @父级名称不能为空！
- 步骤3：无效执行对象的处理属性count(*) @1
- 步骤4：空任务数据的处理 @0
- 步骤5：复杂层级任务的数据构建属性count(*) @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('task');
$table->id->range('1-10');
$table->project->range('1{5}, 2{5}');
$table->execution->range('1{5}, 2{5}');
$table->parent->range('0{3}, 1{4}, 2{3}');
$table->name->range('任务1, 任务2, 任务3, 子任务1, 子任务2, 子任务3, 子任务4, 孙任务1, 孙任务2, 孙任务3');
$table->type->range('devel{5}, test{3}, design{2}');
$table->status->range('wait{7}, doing{2}, done{1}');
$table->assignedTo->range('admin{3}, user1{3}, user2{2}, {2}');
$table->estimate->range('1, 2, 3, 4, 5, 1.5, 2.5, 3.5, 4.5, 5.5');
$table->story->range('1{3}, 2{2}, 0{5}');
$table->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->project->range('0{3}');
$projectTable->name->range('项目1, 项目2, 项目3');
$projectTable->type->range('project{1}, execution{2}');
$projectTable->status->range('doing{3}');
$projectTable->gen(3);

$storyTable = zenData('story');
$storyTable->id->range('1-3');
$storyTable->product->range('1{3}');
$storyTable->title->range('需求1, 需求2, 需求3');
$storyTable->version->range('1{3}');
$storyTable->gen(3);

zenData('user')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 模拟执行对象
$execution = new stdClass();
$execution->id = 1;
$execution->project = 1;

$invalidExecution = new stdClass();
$invalidExecution->id = 0;
$invalidExecution->project = 0;

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：模拟正常批量创建任务数据（无层级关系）
$_POST = array(
    'level' => array(0, 0, 0),
    'name' => array('任务1', '任务2', '任务3')
);
r($taskTest->buildTasksForBatchCreateTest($execution, 1, array())) && p('count(*)') && e('3'); // 步骤1：正常批量创建任务数据构建

// 步骤2：模拟父任务名称为空时的验证
$_POST = array(
    'level' => array(0, 1),
    'name' => array('', '子任务1')
);
r($taskTest->buildTasksForBatchCreateTest($execution, 2, array())) && p() && e('父级名称不能为空！'); // 步骤2：父任务名称为空时的验证

// 步骤3：模拟无效执行对象的处理
$_POST = array(
    'level' => array(0),
    'name' => array('测试任务')
);
r($taskTest->buildTasksForBatchCreateTest($invalidExecution, 1, array())) && p('count(*)') && e('1'); // 步骤3：无效执行对象的处理

// 步骤4：模拟空任务数据
$_POST = array();
r($taskTest->buildTasksForBatchCreateTest($execution, 3, array())) && p() && e('0'); // 步骤4：空任务数据的处理

// 步骤5：模拟正常的包含看板字段的数据
$_POST = array(
    'level' => array(0, 0),
    'name' => array('看板任务1', '看板任务2')
);
r($taskTest->buildTasksForBatchCreateTest($execution, 1, array('laneID' => 2, 'columnID' => 2))) && p('count(*)') && e('2'); // 步骤5：复杂层级任务的数据构建