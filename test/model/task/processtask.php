#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->processTask();
cid=1
pid=1

根据taskID计算wait 未延迟的任务进度 >> ,,normal,75
根据taskID计算wait 延迟的任务进度 >> 1,,normal,75
根据taskID计算doing 延迟的任务进度 >> 1,,normal,80
根据taskID计算doing 未延迟的任务进度 >> ,,normal,80
根据taskID计算done 延迟的任务进度 >> ,,normal,100
根据taskID计算算done 未延迟的任务进度 >> ,,normal,100

*/

$task1 = new stdclass();
$task1->status             = 'wait';
$task1->deadline           = '+1day';
$task1->storyStatus        = 'draft';
$task1->latestStoryVersion = '1';
$task1->storyVersion       = '1';
$task1->product            = '1';
$task1->assignedTo         = 'po82';
$task1->consumed           = '3';
$task1->left               = '1';

$task2 = new stdclass();
$task2->status             = 'doing';
$task2->deadline           = '-1day';
$task2->storyStatus        = 'draft';
$task2->latestStoryVersion = '1';
$task2->storyVersion       = '1';
$task2->product            = '9';
$task2->assignedTo         = '';
$task2->consumed           = '3';
$task2->left               = '1';

$task3 = new stdclass();
$task3->status             = 'doing';
$task3->deadline           = '-1day';
$task3->storyStatus        = 'draft';
$task3->latestStoryVersion = '1';
$task3->storyVersion       = '1';
$task3->product            = '2';
$task3->assignedTo         = '';
$task3->consumed           = '4';
$task3->left               = '1';

$task4 = new stdclass();
$task4->status             = 'doing';
$task4->deadline           = '+1day';
$task4->storyStatus        = 'draft';
$task4->latestStoryVersion = '1';
$task4->storyVersion       = '1';
$task4->product            = '2';
$task4->assignedTo         = '';
$task4->consumed           = '4';
$task4->left               = '1';

$task5 = new stdclass();
$task5->status             = 'done';
$task5->deadline           = '-1day';
$task5->storyStatus        = 'draft';
$task5->latestStoryVersion = '1';
$task5->storyVersion       = '1';
$task5->product            = '9';
$task5->assignedTo         = '';
$task5->consumed           = '11';
$task5->left               = '0';

$task6 = new stdclass();
$task6->status             = 'done';
$task6->deadline           = '+1day';
$task6->storyStatus        = 'draft';
$task6->latestStoryVersion = '1';
$task6->storyVersion       = '1';
$task6->product            = '9';
$task6->assignedTo         = '';
$task6->consumed           = '11';
$task6->left               = '0';

$task = new taskTest();
r($task->processTaskTest($task1)) && p('delay,needConfirm,productType,progress') && e(',,normal,75');   //根据taskID计算wait 未延迟的任务进度
r($task->processTaskTest($task2)) && p('delay,needConfirm,productType,progress') && e('1,,normal,75');  //根据taskID计算wait 延迟的任务进度
r($task->processTaskTest($task3)) && p('delay,needConfirm,productType,progress') && e('1,,normal,80');  //根据taskID计算doing 延迟的任务进度
r($task->processTaskTest($task4)) && p('delay,needConfirm,productType,progress') && e(',,normal,80');   //根据taskID计算doing 未延迟的任务进度
r($task->processTaskTest($task5)) && p('delay,needConfirm,productType,progress') && e(',,normal,100'); //根据taskID计算done 延迟的任务进度
r($task->processTaskTest($task6)) && p('delay,needConfirm,productType,progress') && e(',,normal,100'); //根据taskID计算算done 未延迟的任务进度