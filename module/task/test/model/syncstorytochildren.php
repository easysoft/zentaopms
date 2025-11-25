#!/usr/bin/env php
<?php

/**

title=taskModel->syncStoryToChildren();
timeout=0
cid=18845

- 同步任务 id 1 的需求 2 到其未关联需求的子任务 @6:1;7:0
- 同步任务 id 2 的需求 3 到其未关联需求的子任务 @8:2;9:2
- 同步任务 id 3 的需求 4 到其未关联需求的子任务 @10:2
- 同步任务 id 4 的需求 5 到其未关联需求的子任务 @0
- 同步任务 id 11 的需求 25 到其未关联需求的子任务 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('task')->loadYaml('task')->gen(20);
zenData('story')->gen(50);

$task1 = new stdclass();
$task1->id    = 1;
$task1->story = 2;

$task2 = new stdclass();
$task2->id    = 2;
$task2->story = 3;

$task3 = new stdclass();
$task3->id    = 3;
$task3->story = 4;

$task4 = new stdclass();
$task4->id    = 4;
$task4->story = 5;

$task5 = new stdclass();
$task5->id    = 11;
$task5->story = 25;

$task = new taskTest();

r($task->syncStoryToChildrenTest($task1)) && p() && e('6:1;7:0'); // 同步任务 id 1 的需求 2 到其未关联需求的子任务
r($task->syncStoryToChildrenTest($task2)) && p() && e('8:2;9:2'); // 同步任务 id 2 的需求 3 到其未关联需求的子任务
r($task->syncStoryToChildrenTest($task3)) && p() && e('10:2');    // 同步任务 id 3 的需求 4 到其未关联需求的子任务
r($task->syncStoryToChildrenTest($task4)) && p() && e('0');       // 同步任务 id 4 的需求 5 到其未关联需求的子任务
r($task->syncStoryToChildrenTest($task5)) && p() && e('0');       // 同步任务 id 11 的需求 25 到其未关联需求的子任务