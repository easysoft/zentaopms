#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserTasks();
cid=1
pid=1

获取人员任务数 >> po82:2;user92:2;

*/

$report = new reportTest();

r($report->getUserTasksTest()) && p() && e('po82:2;user92:2;'); // 获取人员任务数