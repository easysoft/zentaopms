#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('testtask')->gen('100');
zdTable('user')->gen(20);

su('admin');

/**

title=测试 reportModel->getUserTestTasks();
cid=1
pid=1

测试获取测试任务数 >> user3:9;user4:9;user7:8;user8:8;user11:8;user12:8;

*/

$report = new reportTest();

r($report->getUserTestTasksTest()) && p() && e('user3:9;user4:9;user7:8;user8:8;user11:8;user12:8;'); // 测试获取测试任务数
