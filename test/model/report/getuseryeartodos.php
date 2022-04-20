#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserYearTodos();
cid=1
pid=1

测试获取本年度 admin 的产品数 >> count:3;undone:3;done:0;
测试获取本年度 dev17 的产品数 >> count:2;undone:2;done:0;
测试获取本年度 dev20 的产品数 >> count:2;undone:2;done:0;
测试获取本年度 admin dev17 的产品数 >> count:5;undone:5;done:0;
测试获取本年度 admin dev20 的产品数 >> count:5;undone:5;done:0;

*/
$account = array('admin', 'dev17', 'dev20', 'admin,dev17', 'admin,dev20');

$report = new reportTest();

r($report->getUserYearTodosTest($account[0])) && p() && e('count:3;undone:3;done:0;'); // 测试获取本年度 admin 的产品数
r($report->getUserYearTodosTest($account[1])) && p() && e('count:2;undone:2;done:0;'); // 测试获取本年度 dev17 的产品数
r($report->getUserYearTodosTest($account[2])) && p() && e('count:2;undone:2;done:0;'); // 测试获取本年度 dev20 的产品数
r($report->getUserYearTodosTest($account[3])) && p() && e('count:5;undone:5;done:0;'); // 测试获取本年度 admin dev17 的产品数
r($report->getUserYearTodosTest($account[4])) && p() && e('count:5;undone:5;done:0;'); // 测试获取本年度 admin dev20 的产品数