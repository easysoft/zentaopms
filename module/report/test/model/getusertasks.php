#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('task')->loadYaml('task')->gen('30');
zenData('project')->loadYaml('execution')->gen('130');
zenData('user')->gen(10);

su('admin');

/**

title=测试 reportModel->getUserTasks();
cid=1
pid=1

*/

$report = new reportTest();

r($report->getUserTasksTest()) && p() && e('admin:3;user1:3;'); // 获取人员任务数
