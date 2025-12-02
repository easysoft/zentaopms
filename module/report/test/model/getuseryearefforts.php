#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('effort')->loadYaml('effort')->gen(50);
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearEfforts();
cid=18176
pid=1

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportTest();

r($report->getUserYearEffortsTest($account[0])) && p('count,consumed') && e('1,1');     // 获取本年度 admin 任务数
r($report->getUserYearEffortsTest($account[1])) && p('count,consumed') && e('3,78');    // 获取本年度 po82 任务数
r($report->getUserYearEffortsTest($account[2])) && p('count,consumed') && e('3,93');    // 获取本年度 user92 任务数
r($report->getUserYearEffortsTest($account[3])) && p('count,consumed') && e('4,79');    // 获取本年度 admin po82 任务数
r($report->getUserYearEffortsTest($account[4])) && p('count,consumed') && e('4,94');    // 获取本年度 admin user92 任务数
r($report->getUserYearEffortsTest($account[5])) && p('count,consumed') && e('50,2175'); // 获取本年度 所有用户 任务数
