#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getUserYearLogins();
cid=1
pid=1

测试获取本年度 admin 的登录次数 >> 0
测试获取本年度 dev17 的登录次数 >> 0
测试获取本年度 test18 的登录次数 >> 0
测试获取本年度 admin dev17 的登录次数 >> 0
测试获取本年度 admin test18 的登录次数 >> 0

*/
$account = array('admin', 'dev17', 'test18', 'admin,dev17', 'admin,test18');

$report = new reportTest();

r($report->getUserYearLoginsTest($account[0])) && p() && e('0');// 测试获取本年度 admin 的登录次数
r($report->getUserYearLoginsTest($account[1])) && p() && e('0');// 测试获取本年度 dev17 的登录次数
r($report->getUserYearLoginsTest($account[2])) && p() && e('0');// 测试获取本年度 test18 的登录次数
r($report->getUserYearLoginsTest($account[3])) && p() && e('0');// 测试获取本年度 admin dev17 的登录次数
r($report->getUserYearLoginsTest($account[4])) && p() && e('0');// 测试获取本年度 admin test18 的登录次数