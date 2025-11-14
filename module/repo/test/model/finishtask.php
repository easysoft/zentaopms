#!/usr/bin/env php
<?php

/**

title=测试 repoModel::finishTask();
timeout=0
cid=18044

- 执行repoTest模块的finishTaskTest方法，参数是$task1, $params1, $action, $changes  @1
- 执行repoTest模块的finishTaskTest方法，参数是$task2, $params2, $action, $changes  @1
- 执行repoTest模块的finishTaskTest方法，参数是$task3, $params3, $action, $changes  @1
- 执行repoTest模块的finishTaskTest方法，参数是null, $params1, $action, $changes  @0
- 执行repoTest模块的finishTaskTest方法，参数是$task1, $invalidParams, $action, $changes  @0
- 执行repoTest模块的finishTaskTest方法，参数是$task1, $params1, null, $changes  @0
- 执行repoTest模块的finishTaskTest方法，参数是$invalidTask, $params1, $action, $changes  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zendata('task')->loadYaml('task_finishtask', false, 2)->gen(10);
zendata('taskteam')->gen(5);
zendata('user')->gen(10);
zendata('action')->gen(5);

su('admin');

$repoTest = new repoTest();

// 构造正常测试任务对象
$task1 = new stdclass();
$task1->id = 1;
$task1->name = '测试任务1';
$task1->status = 'doing';
$task1->consumed = 5.0;
$task1->left = 3.0;
$task1->openedBy = 'admin';
$task1->realStarted = '2024-01-01 09:00:00';
$task1->team = '';
$task1->mode = '';

// 构造带团队的任务对象
$task2 = new stdclass();
$task2->id = 2;
$task2->name = '团队任务';
$task2->status = 'doing';
$task2->consumed = 8.0;
$task2->left = 2.0;
$task2->openedBy = 'user1';
$task2->realStarted = '2024-01-02 10:00:00';
$task2->team = 'team1,team2';
$task2->mode = 'linear';

// 构造零工时任务对象
$task3 = new stdclass();
$task3->id = 3;
$task3->name = '零工时任务';
$task3->status = 'doing';
$task3->consumed = 0.0;
$task3->left = 0.0;
$task3->openedBy = 'admin';
$task3->realStarted = '';
$task3->team = '';
$task3->mode = '';

// 构造无效任务对象（缺少必要属性）
$invalidTask = new stdclass();
$invalidTask->name = '无效任务';

// 构造参数
$params1 = array('consumed' => 2.0, 'left' => 0);
$params2 = array('consumed' => 3.0, 'left' => 0);
$params3 = array('consumed' => 1.0, 'left' => 0);
$invalidParams = array(); // 缺少consumed参数

// 构造动作对象
$action = new stdclass();
$action->id = 1;
$action->action = 'commit';
$action->extra = 'abc123';

// 构造变更数组
$changes = array('status' => array('doing', 'done'));

// 执行测试步骤
r($repoTest->finishTaskTest($task1, $params1, $action, $changes)) && p() && e('1');
r($repoTest->finishTaskTest($task2, $params2, $action, $changes)) && p() && e('1');
r($repoTest->finishTaskTest($task3, $params3, $action, $changes)) && p() && e('1');
r($repoTest->finishTaskTest(null, $params1, $action, $changes)) && p() && e('0');
r($repoTest->finishTaskTest($task1, $invalidParams, $action, $changes)) && p() && e('0');
r($repoTest->finishTaskTest($task1, $params1, null, $changes)) && p() && e('0');
r($repoTest->finishTaskTest($invalidTask, $params1, $action, $changes)) && p() && e('0');