#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('user')->gen(10);
zdTable('task')->config('task', true)->gen(30);

su('admin');

/**

title=taskModel->processExportTasks();
timeout=0
cid=1

*/

$taskTester = new taskTest();

$taskIdList = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
r($taskTester->processExportTasksTest(array()))     && p()         && e('0');          // 测试空数据
r($taskTester->processExportTasksTest($taskIdList)) && p('9:name') && e('开发任务20'); // 测试处理任务数据
