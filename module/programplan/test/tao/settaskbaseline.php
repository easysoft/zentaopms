#!/usr/bin/env php
<?php

/**

title=测试 loadModel->setTaskBaseline()
cid=0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('programplan');

$oldTasks[1] = new stdclass();
$oldTasks[1]->version    = 2;
$oldTasks[1]->name       = 'Old task1';
$oldTasks[1]->estStarted = '2023-12-28';
$oldTasks[1]->deadline   = '2023-12-28';
$oldTasks[2] = new stdclass();
$oldTasks[2]->version    = 1;
$oldTasks[2]->name       = 'Old task2';
$oldTasks[2]->estStarted = '2023-11-28';
$oldTasks[2]->deadline   = '2023-11-28';

$newTasks[1] = new stdclass();
$newTasks[1]->version    = 3;
$newTasks[1]->name       = 'New task1';
$newTasks[1]->estStarted = '2023-11-28';
$newTasks[1]->deadline   = '2023-11-28';

r($tester->programplan->setTaskBaseline(array(), array()))     && p() && e(0); //传入空参数
r($tester->programplan->setTaskBaseline($oldTasks, array()))   && p() && e(0); //只传入 oldTasks 参数
r($tester->programplan->setTaskBaseline(array(), $newTasks))   && p('1:name,version,deadline')   && e('New task1,3,2023-11-28'); //只传入 newTasks 参数

$tasks = $tester->programplan->setTaskBaseline($oldTasks, $newTasks);
r($tasks)           && p('1:name,version,deadline') && e('Old task1,2,2023-12-28');   //只传入正确参数，检查返回数据
r(isset($tasks[2])) && p()                          && e('0');                        //只传入正确参数，检查不存在的返回数据
