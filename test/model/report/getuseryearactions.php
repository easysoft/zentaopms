#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserYearActions();
cid=1
pid=1

测试获取本年度 admin 的操作数 >> 34
测试获取本年度 dev17 的操作数 >> 33
测试获取本年度 test18 的操作数 >> 33
测试获取本年度 admin dev17 的操作数 >> 67
测试获取本年度 admin test18 的操作数 >> 67

*/
$accounts = array('admin', 'dev17', 'test18', 'admin,dev17', 'admin,test18');

$report = new reportTest();

r($report->getUserYearActionsTest($accounts[0])) && p() && e('34'); // 测试获取本年度 admin 的操作数
r($report->getUserYearActionsTest($accounts[1])) && p() && e('33'); // 测试获取本年度 dev17 的操作数
r($report->getUserYearActionsTest($accounts[2])) && p() && e('33'); // 测试获取本年度 test18 的操作数
r($report->getUserYearActionsTest($accounts[3])) && p() && e('67'); // 测试获取本年度 admin dev17 的操作数
r($report->getUserYearActionsTest($accounts[4])) && p() && e('67'); // 测试获取本年度 admin test18 的操作数