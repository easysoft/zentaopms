#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->processNoWaitGanttData();
cid=0

- 检查处理后的任务个数 @5
- 检查处理后的进行中的任务数据
 - 属性id @1-2
 - 属性start_date @01-03-2026
 - 属性endDate @31-03-2026
- 检查处理后的暂停的任务数据
 - 属性id @1-3
 - 属性start_date @01-03-2026
 - 属性endDate @15-03-2026
- 检查处理后的取消的任务数据
 - 属性id @1-4
 - 属性start_date @01-03-2026
 - 属性endDate @01-04-2026
- 检查处理后的完成的任务数据
 - 属性id @1-5
 - 属性start_date @01-03-2026
 - 属性endDate @11-03-2026

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('action')->gen(0);

$tasks = array();
$tasks[1] = new stdclass();
$tasks[1]->id           = 1;
$tasks[1]->type         = 'stage';
$tasks[1]->rawStatus    = 'doing';
$tasks[1]->status       = '进行中';
$tasks[1]->deadline     = '2026-03-31';
$tasks[1]->realBegan    = '2026-03-01';
$tasks[1]->realEnd      = '';
$tasks[1]->canceledDate = '';
$tasks[2] = new stdclass();
$tasks[2]->id           = '1-1';
$tasks[2]->type         = 'task';
$tasks[2]->rawStatus    = 'wait';
$tasks[2]->status       = 'wait';
$tasks[2]->deadline     = '2026-03-31';
$tasks[2]->realBegan    = '2026-03-01';
$tasks[2]->realEnd      = '';
$tasks[2]->canceledDate = '';
$tasks[3] = new stdclass();
$tasks[3]->id           = '1-2';
$tasks[3]->type         = 'task';
$tasks[3]->rawStatus    = 'doing';
$tasks[3]->status       = 'doing';
$tasks[3]->deadline     = '2026-03-31';
$tasks[3]->realBegan    = '2026-03-01';
$tasks[3]->realEnd      = '';
$tasks[3]->canceledDate = '';
$tasks[4] = new stdclass();
$tasks[4]->id           = '1-3';
$tasks[4]->type         = 'task';
$tasks[4]->rawStatus    = 'pause';
$tasks[4]->status       = 'pause';
$tasks[4]->deadline     = '2026-03-31';
$tasks[4]->realBegan    = '2026-03-01';
$tasks[4]->realEnd      = '';
$tasks[4]->canceledDate = '';
$tasks[5] = new stdclass();
$tasks[5]->id           = '1-4';
$tasks[5]->type         = 'task';
$tasks[5]->rawStatus    = 'cancel';
$tasks[5]->status       = 'cancel';
$tasks[5]->deadline     = '2026-03-31';
$tasks[5]->realBegan    = '2026-03-01';
$tasks[5]->realEnd      = '';
$tasks[5]->canceledDate = '2026-04-01';
$tasks[6] = new stdclass();
$tasks[6]->id           = '1-5';
$tasks[6]->type         = 'task';
$tasks[6]->rawStatus    = 'done';
$tasks[6]->status       = 'done';
$tasks[6]->deadline     = '2026-03-31';
$tasks[6]->realBegan    = '2026-03-01';
$tasks[6]->realEnd      = '2026-03-11';
$tasks[6]->canceledDate = '';

$pauseAction = new stdclass();
$pauseAction->objectType = 'task';
$pauseAction->objectID = '3';
$pauseAction->action = 'paused';
$pauseAction->date = '2026-03-15';

global $tester;
$programplanModel = $tester->loadModel('programplan');
$programplanModel->dao->insert(TABLE_ACTION)->data($pauseAction)->exec();

$processedTasks = $programplanModel->processNoWaitGanttData($tasks);
r(count($processedTasks)) && p() && e('5'); //检查处理后的任务个数
r($processedTasks[1]) && p('id,start_date,endDate') && e('1-2,01-03-2026,31-03-2026'); //检查处理后的进行中的任务数据
r($processedTasks[2]) && p('id,start_date,endDate') && e('1-3,01-03-2026,15-03-2026'); //检查处理后的暂停的任务数据
r($processedTasks[3]) && p('id,start_date,endDate') && e('1-4,01-03-2026,01-04-2026'); //检查处理后的取消的任务数据
r($processedTasks[4]) && p('id,start_date,endDate') && e('1-5,01-03-2026,11-03-2026'); //检查处理后的完成的任务数据