#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('execution', true)->gen(10);
zdTable('task')->config('task', true)->gen(10);

/**

title=taskModel->cancelParentTask();
timeout=0
cid=2

*/

$taskIdList = array(1, 6, 7);

$taskTester = new taskTest();

r($taskTester->cancelParentTaskTest($taskIdList[0])) && p()         && e('0');        // 测试取消普通任务
r($taskTester->cancelParentTaskTest($taskIdList[1])) && p('action') && e('canceled'); // 测试取消父任务
r($taskTester->cancelParentTaskTest($taskIdList[2])) && p()         && e('0');        // 测试取消子任务
