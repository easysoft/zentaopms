#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseModal();
timeout=0
cid=18948

- 步骤1: recordworkhour场景因createLink未定义而返回错误属性error @Call to undefined function createLink()
- 步骤2: execution kanban场景返回refreshKanban回调属性callback @refreshKanban()
- 步骤3: lite kanban场景返回refreshKanban回调属性callback @refreshKanban()
- 步骤4: taskkanban场景返回refreshKanban回调属性callback @refreshKanban()
- 步骤5: edittask场景load为false属性load @0
- 步骤6: 普通场景load为true属性load @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('1{2},3{3},5{5}');
$task->gen(10);

$execution = zenData('project');
$execution->id->range('1-10');
$execution->type->range('stage{2},kanban{3},sprint{5}');
$execution->gen(10);

su('admin');

$taskTest = new taskZenTest();

global $tester;

// 测试1: recordworkhour场景,返回closeModal为false
$task1 = new stdClass();
$task1->id = 1;
$task1->execution = 1;
$tester->app->rawMethod = 'recordworkhour';
r($taskTest->responseModalTest($task1, '')) && p('error') && e('Call to undefined function createLink()'); // 步骤1: recordworkhour场景因createLink未定义而返回错误

// 测试2: execution类型为kanban且tab为execution场景
$task2 = new stdClass();
$task2->id = 2;
$task2->execution = 3;
$tester->app->rawMethod = 'edit';
$tester->app->tab = 'execution';
r($taskTest->responseModalTest($task2, '')) && p('callback') && e('refreshKanban()'); // 步骤2: execution kanban场景返回refreshKanban回调

// 测试3: lite kanban场景
$task3 = new stdClass();
$task3->id = 3;
$task3->execution = 3;
$tester->app->rawMethod = 'edit';
$tester->app->tab = 'project';
$tester->config->vision = 'lite';
$tester->session->kanbanview = 'kanban';
r($taskTest->responseModalTest($task3, '')) && p('callback') && e('refreshKanban()'); // 步骤3: lite kanban场景返回refreshKanban回调

// 测试4: from参数为taskkanban场景
$task4 = new stdClass();
$task4->id = 4;
$task4->execution = 1;
$tester->app->rawMethod = 'edit';
$tester->app->tab = 'execution';
$tester->config->vision = 'rnd';
$tester->session->kanbanview = '';
r($taskTest->responseModalTest($task4, 'taskkanban')) && p('callback') && e('refreshKanban()'); // 步骤4: taskkanban场景返回refreshKanban回调

// 测试5: from参数为edittask场景
$task5 = new stdClass();
$task5->id = 5;
$task5->execution = 1;
$tester->app->rawMethod = 'edit';
$tester->app->tab = 'execution';
$tester->config->vision = 'rnd';
$tester->session->kanbanview = '';
r($taskTest->responseModalTest($task5, 'edittask')) && p('load') && e('0'); // 步骤5: edittask场景load为false

// 测试6: 普通场景
$task6 = new stdClass();
$task6->id = 6;
$task6->execution = 1;
$tester->app->rawMethod = 'edit';
$tester->app->tab = 'execution';
$tester->config->vision = 'rnd';
$tester->session->kanbanview = '';
r($taskTest->responseModalTest($task6, '')) && p('load') && e('1'); // 步骤6: 普通场景load为true