#!/usr/bin/env php
<?php

/**

title=测试 taskZen::generalCreateResponse();
timeout=0
cid=0

- 步骤1：continueAdding选项
 - 属性result @success
 - 属性message @成功添加，继续为该软件需求添加任务
- 步骤2：continueAdding带story和module
 - 属性result @success
 - 属性load @task-create-1-2-3.html
- 步骤3：toTaskList选项
 - 属性result @success
 - 属性load @execution-task-1-unclosed-0-id_desc.html
- 步骤4：其他选项默认处理属性result @success
- 步骤5：空字符串处理属性result @success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$task = zenData('task');
$task->id->range('1-10');
$task->name->range('任务{1-10}');
$task->execution->range('1-3');
$task->story->range('0,1,2,0,1');
$task->module->range('1-5');
$task->status->range('wait');
$task->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{1-5}');
$project->type->range('execution');
$project->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskZenTest = new taskZenTest();

// 5. 测试步骤 - 必须包含至少5个测试步骤

// 创建测试任务对象
$testTask = new stdClass();
$testTask->execution = 1;
$testTask->story = 0;
$testTask->module = 0;

r($taskZenTest->generalCreateResponseTest($testTask, 1, 'continueAdding')) && p('result,message') && e('success,成功添加，继续为该软件需求添加任务'); // 步骤1：continueAdding选项

$testTask->story = 2;
$testTask->module = 3;
r($taskZenTest->generalCreateResponseTest($testTask, 2, 'continueAdding')) && p('result,load') && e('success,task-create-1-2-3.html'); // 步骤2：continueAdding带story和module

r($taskZenTest->generalCreateResponseTest($testTask, 1, 'toTaskList')) && p('result,load') && e('success,execution-task-1-unclosed-0-id_desc.html'); // 步骤3：toTaskList选项

r($taskZenTest->generalCreateResponseTest($testTask, 1, 'toStoryList')) && p('result') && e('success'); // 步骤4：其他选项默认处理

r($taskZenTest->generalCreateResponseTest($testTask, 1, '')) && p('result') && e('success'); // 步骤5：空字符串处理