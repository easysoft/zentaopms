#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserYearEfforts();
cid=1
pid=1

获取本年度 admin 任务数 >> 599,4188
获取本年度 po82 任务数 >> 1,3
获取本年度 user92 任务数 >> 0,0
获取本年度 admin po82 任务数 >> 600,4191
获取本年度 admin user92 任务数 >> 599,4188

*/
$account = array('admin', 'po82', 'user92', 'admin,po82', 'admin,user92');

$report = new reportTest();

r($report->getUserYearEffortsTest($account[0])) && p('count,consumed') && e('599,4188'); // 获取本年度 admin 任务数
r($report->getUserYearEffortsTest($account[1])) && p('count,consumed') && e('1,3');      // 获取本年度 po82 任务数
r($report->getUserYearEffortsTest($account[2])) && p('count,consumed') && e('0,0');      // 获取本年度 user92 任务数
r($report->getUserYearEffortsTest($account[3])) && p('count,consumed') && e('600,4191'); // 获取本年度 admin po82 任务数
r($report->getUserYearEffortsTest($account[4])) && p('count,consumed') && e('599,4188'); // 获取本年度 admin user92 任务数