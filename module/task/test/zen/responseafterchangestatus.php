#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterChangeStatus();
timeout=0
cid=18944

- 步骤1：JSON视图类型返回属性result @success
- 步骤2：API模式下的返回属性result @success
- 步骤3：模态窗口中recordworkhour方法
 - 属性result @success
 - 属性message @保存成功
- 步骤4：模态窗口中看板类型执行的返回
 - 属性result @success
 - 属性message @保存成功
- 步骤5：普通默认返回
 - 属性result @success
 - 属性message @保存成功
 - 属性load @1
 - 属性closeModal @1

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

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('执行{1-5}');
$execution->type->range('sprint,stage,kanban');
$execution->status->range('wait,doing,suspended,closed');
$execution->gen(5);

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
global $tester;

// 测试步骤1：测试JSON视图类型的返回
r($taskTest->responseAfterChangeStatusTest($taskObj, '', 'json', false)) && p('result') && e('success'); // 步骤1：JSON视图类型返回

// 测试步骤2：测试API模式的返回
if(!defined('RUN_MODE')) define('RUN_MODE', 'api');
r($taskTest->responseAfterChangeStatusTest($taskObj, '', '', false)) && p('result') && e('success'); // 步骤2：API模式下的返回

// 测试步骤3：测试模态窗口中recordworkhour方法的返回
$tester->app->rawMethod = 'recordworkhour';
r($taskTest->responseAfterChangeStatusTest($taskObj, '', '', true)) && p('result,message') && e('success,保存成功'); // 步骤3：模态窗口中recordworkhour方法

// 测试步骤4：测试模态窗口中看板类型执行的返回
$tester->app->rawMethod = 'edit';
$tester->app->tab = 'execution';
r($taskTest->responseAfterChangeStatusTest($kanbanTaskObj, 'taskkanban', '', true)) && p('result,message') && e('success,保存成功'); // 步骤4：模态窗口中看板类型执行的返回

// 测试步骤5：测试普通默认返回
$tester->app->rawMethod = 'edit';
$tester->app->tab = 'task';
r($taskTest->responseAfterChangeStatusTest($taskObj, '', '', false)) && p('result,message,load,closeModal') && e('success,保存成功,1,1'); // 步骤5：普通默认返回