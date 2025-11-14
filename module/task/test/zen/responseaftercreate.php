#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterCreate();
timeout=0
cid=18945

- 步骤1：正常情况continueAdding
 - 属性result @success
 - 属性message @保存成功
- 步骤2：toTaskList选项
 - 属性result @success
 - 属性message @保存成功
- 步骤3：API模式
 - 属性result @success
 - 属性id @1
- 步骤4：看板执行模式
 - 属性result @success
 - 属性callback @refreshKanban()
- 步骤5：toStoryList选项
 - 属性result @success
 - 属性message @保存成功

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
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
$project->type->range('project,sprint,kanban');
$project->status->range('wait,doing,suspended,closed');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 创建测试数据对象
$taskObj = new stdClass();
$taskObj->id = 1;
$taskObj->execution = 1;

$executionObj = new stdClass();
$executionObj->id = 1;
$executionObj->type = 'sprint';

$kanbanExecutionObj = new stdClass();
$kanbanExecutionObj->id = 2;
$kanbanExecutionObj->type = 'kanban';

// 6. 模拟不同的应用状态
global $app;
$app->viewType = 'html';
$app->tab = 'task';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->responseAfterCreateTest($taskObj, $executionObj, 'continueAdding')) && p('result,message') && e('success,保存成功'); // 步骤1：正常情况continueAdding
r($taskTest->responseAfterCreateTest($taskObj, $executionObj, 'toTaskList')) && p('result,message') && e('success,保存成功'); // 步骤2：toTaskList选项
$app->viewType = 'json';
r($taskTest->responseAfterCreateTest($taskObj, $executionObj, 'continueAdding')) && p('result,id') && e('success,1'); // 步骤3：API模式
$app->viewType = 'html';
$app->tab = 'execution';
r($taskTest->responseAfterCreateTest($taskObj, $kanbanExecutionObj, 'continueAdding')) && p('result,callback') && e('success,refreshKanban()'); // 步骤4：看板执行模式
$app->tab = 'task';
r($taskTest->responseAfterCreateTest($taskObj, $executionObj, 'toStoryList')) && p('result,message') && e('success,保存成功'); // 步骤5：toStoryList选项