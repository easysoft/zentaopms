#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('task')->config('task')->gen(9);

/**

title=taskModel->getRemindBugLink();
timeout=0
cid=1

*/

global $tester;
$taskModel = $tester->loadModel('task');

$oldTask = $taskModel->getByID(1);
$newTask = clone $oldTask;
$newTask->status = 'done';

$changes = common::createChanges($oldTask, $newTask);

r($taskModel->getRemindBugLink($newTask, $changes)) && p('result') && e('success'); // 获取返回的链接
