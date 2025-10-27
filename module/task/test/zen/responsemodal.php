#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseModal();
timeout=0
cid=0

- 步骤1：recordworkhour方法返回
 - 属性result @success
 - 属性message @保存成功
- 步骤2：kanban执行类型返回
 - 属性result @success
 - 属性message @保存成功
- 步骤3：非kanban执行类型返回
 - 属性result @success
 - 属性message @保存成功
- 步骤4：edittask来源返回
 - 属性result @success
 - 属性message @保存成功
- 步骤5：其他来源参数返回属性result @success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1-3');
$task->execution->range('1-3');
$task->name->range('Task{1-10}');
$task->type->range('devel,test,design,study,misc');
$task->status->range('wait,doing,done');
$task->assignedTo->range('admin,user1,user2');
$task->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{1-5}');
$project->type->range('project');
$project->status->range('wait,doing,suspended,closed');
$project->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskZenTest();

// 5. 创建测试数据对象
$taskObj = new stdClass();
$taskObj->id = 1;
$taskObj->execution = 1;

$kanbanTaskObj = new stdClass();
$kanbanTaskObj->id = 2;
$kanbanTaskObj->execution = 3;

// 6. 模拟不同的应用状态
global $app;

// 测试步骤1：测试recordworkhour方法的返回
$app->rawMethod = 'recordworkhour';
r($taskTest->responseModalTest($taskObj, '')) && p('result,message') && e('success,保存成功'); // 步骤1：recordworkhour方法返回

// 测试步骤2：测试普通kanban执行类型的返回
$app->rawMethod = 'edit';
$app->tab = 'execution';
r($taskTest->responseModalTest($kanbanTaskObj, '')) && p('result,message') && e('success,保存成功'); // 步骤2：kanban执行类型返回

// 测试步骤3：测试非kanban执行类型的返回
$app->tab = 'execution';
r($taskTest->responseModalTest($taskObj, '')) && p('result,message') && e('success,保存成功'); // 步骤3：非kanban执行类型返回

// 测试步骤4：测试edittask来源的返回
$app->rawMethod = 'edit';
$app->tab = 'task';
r($taskTest->responseModalTest($taskObj, 'edittask')) && p('result,message') && e('success,保存成功'); // 步骤4：edittask来源返回

// 测试步骤5：测试不同参数覆盖
$app->rawMethod = 'edit';
$app->tab = 'task';
r($taskTest->responseModalTest($taskObj, 'other')) && p('result') && e('success'); // 步骤5：其他来源参数返回