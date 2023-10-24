#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->addTaskEstimate();
cid=1
pid=1

插入task为1 left为0 consumed为3的任务 >> 601,1,0,3
插入task为601 left为0 consumed为3的任务 >> 602,601,0,3
插入task为601 left为1 consumed为4的任务 >> 603,602,1,4
插入task为904 left为3 consumed为6的任务 >> 604,904,3,6
插入task为907 left为6 consumed为9的任务 >> 605,907,6,9

*/

$record1 = new stdclass();
$record1->account  = 'po82';
$record1->task     = 1;
$record1->left     = 0;
$record1->consumed = 3;

$record2 = new stdclass();
$record2->account  = 'po82';
$record2->task     = 601;
$record2->left     = 0;
$record2->consumed = 3;

$record3 = new stdclass();
$record3->account  = 'po82';
$record3->task     = 602;
$record3->left     = 1;
$record3->consumed = 4;

$record4 = new stdclass();
$record4->account  = 'po82';
$record4->task     = 904;
$record4->left     = 3;
$record4->consumed = 6;

$record5 = new stdclass();
$record5->account  = 'po82';
$record5->task     = 907;
$record5->left     = 6;
$record5->consumed = 9;

$task = new taskTest();
r($task->addTaskEstimateTest($record1)) && p('id,task,left,consumed') && e("601,1,0,3");   // 插入task为1 left为0 consumed为3的任务
r($task->addTaskEstimateTest($record2)) && p('id,task,left,consumed') && e("602,601,0,3"); // 插入task为601 left为0 consumed为3的任务
r($task->addTaskEstimateTest($record3)) && p('id,task,left,consumed') && e("603,602,1,4"); // 插入task为601 left为1 consumed为4的任务
r($task->addTaskEstimateTest($record4)) && p('id,task,left,consumed') && e("604,904,3,6"); // 插入task为904 left为3 consumed为6的任务
r($task->addTaskEstimateTest($record5)) && p('id,task,left,consumed') && e("605,907,6,9"); // 插入task为907 left为6 consumed为9的任务
