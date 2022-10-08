#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserTestTasks();
cid=1
pid=1

测试获取测试任务数 >> user3:9;user4:9;user7:8;user8:8;user11:8;user12:8;

*/

$report = new reportTest();

r($report->getUserTestTasksTest()) && p() && e('user3:9;user4:9;user7:8;user8:8;user11:8;user12:8;'); // 测试获取测试任务数