#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getStatusOverview();
cid=1
pid=1

测试获取 story 状态数量 >> 总需求 &nbsp; 21<br />未完成 &nbsp; 16
测试获取 task 状态数量 >> 总任务 &nbsp; 21<br />未完成 &nbsp; 6
测试获取 bug 状态数量 >> 总Bug &nbsp; 21<br />未解决 &nbsp; 1

*/
$objectType = array('story', 'task', 'bug');
$statusStat = array('active' => 1, 'wait' => 2, 'doing' => 3, 'done' => 4, 'closed' => 5, 'cancel' => 6);

$report = new reportTest();

r($report->getStatusOverviewTest($objectType[0], $statusStat)) && p() && e('总需求 &nbsp; 21<br />未完成 &nbsp; 16'); // 测试获取 story 状态数量
r($report->getStatusOverviewTest($objectType[1], $statusStat)) && p() && e('总任务 &nbsp; 21<br />未完成 &nbsp; 6');  // 测试获取 task 状态数量
r($report->getStatusOverviewTest($objectType[2], $statusStat)) && p() && e('总Bug &nbsp; 21<br />未解决 &nbsp; 1');   // 测试获取 bug 状态数量