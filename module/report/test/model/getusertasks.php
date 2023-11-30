#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('task')->config('task')->gen('30');
zdTable('project')->config('execution')->gen('130');
zdTable('user')->gen(10);

su('admin');

/**

title=测试 reportModel->getUserTasks();
cid=1
pid=1

*/

$report = new reportTest();

r($report->getUserTasksTest()) && p() && e('admin:3;user1:3;'); // 获取人员任务数
