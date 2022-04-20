#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserYearExecutions();
cid=1
pid=1

测试获取本年度 admin 的执行id >> 120
测试获取本年度 dev17 的执行id >> 0
测试获取本年度 test18 的执行id >> 127
测试获取本年度 admin dev17 的执行id >> 120
测试获取本年度 admin test18 的执行id >> 127,120

*/
$account = array('admin', 'dev17', 'test18', 'admin,dev17', 'admin,test18');

$report = new reportTest();

r($report->getUserYearExecutionsTest($account[0])) && p() && e('120');     // 测试获取本年度 admin 的执行id
r($report->getUserYearExecutionsTest($account[1])) && p() && e('0');       // 测试获取本年度 dev17 的执行id
r($report->getUserYearExecutionsTest($account[2])) && p() && e('127');     // 测试获取本年度 test18 的执行id
r($report->getUserYearExecutionsTest($account[3])) && p() && e('120');     // 测试获取本年度 admin dev17 的执行id
r($report->getUserYearExecutionsTest($account[4])) && p() && e('127,120'); // 测试获取本年度 admin test18 的执行id