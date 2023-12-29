#!/usr/bin/env php
<?php

/**

title=测试 loadModel->getTaskDateLimit()
cid=0

- 传入未填写预计日期的任务数据
 - 属性start @2023-09-21
 - 属性end @2023-12-21
 - 属性realBegan @``
 - 属性realEnd @``
- 传入未开始的任务数据
 - 属性start @2023-09-28
 - 属性end @2023-09-29
 - 属性realBegan @``
 - 属性realEnd @``
- 传入进行中的任务数据
 - 属性start @2023-10-01
 - 属性end @2023-10-01
 - 属性realBegan @2023-10-01
 - 属性realEnd @``
- 传入已完成的任务数据
 - 属性start @2023-10-01
 - 属性end @2023-10-02
 - 属性realBegan @2023-10-01
 - 属性realEnd @2023-10-02

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('programplan');

$execution = new stdclass();
$execution->begin = '2023-09-21';
$execution->end   = '2023-12-21';

$task = new stdclass();
$task->estStarted   = '';
$task->deadline     = '';
$task->realStarted  = '';
$task->finishedDate = '';
$task->status       = 'wait';

r($tester->programplan->getTaskDateLimit($task, $execution)) && p('start,end,realBegan,realEnd') && e('2023-09-21,2023-12-21,``,``'); //传入未填写预计日期的任务数据

$task->estStarted   = '2023-09-28';
$task->deadline     = '2023-09-29';
r($tester->programplan->getTaskDateLimit($task))    && p('start,end,realBegan,realEnd') && e('2023-09-28,2023-09-29,``,``'); //传入未开始的任务数据

$task->realStarted = '2023-10-01';
$task->status      = 'doing';
r($tester->programplan->getTaskDateLimit($task))    && p('start,end,realBegan,realEnd') && e('2023-10-01,2023-10-01,2023-10-01,``'); //传入进行中的任务数据

$task->finishedDate = '2023-10-02';
$task->status       = 'done';
r($tester->programplan->getTaskDateLimit($task))    && p('start,end,realBegan,realEnd') && e('2023-10-01,2023-10-02,2023-10-01,2023-10-02'); //传入已完成的任务数据
