#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getYearCaseStat();
cid=1
pid=1

测试获取本年度 admin 的用例数 >> pass:40;fail:30;
测试获取本年度 dev17 的用例数 >> 0
测试获取本年度 test18 的用例数 >> 0
测试获取本年度 admin dev17 的用例数 >> pass:40;fail:30;
测试获取本年度 admin test18 的用例数 >> pass:40;fail:30;

*/
$account = array('admin', 'dev17', 'test18', 'admin,dev17', 'admin,test18');

$report = new reportTest();

r($report->getYearCaseStatTest($account[0])) && p() && e('pass:40;fail:30;'); // 测试获取本年度 admin 的用例数
r($report->getYearCaseStatTest($account[1])) && p() && e('0');                // 测试获取本年度 dev17 的用例数
r($report->getYearCaseStatTest($account[2])) && p() && e('0');                // 测试获取本年度 test18 的用例数
r($report->getYearCaseStatTest($account[3])) && p() && e('pass:40;fail:30;'); // 测试获取本年度 admin dev17 的用例数
r($report->getYearCaseStatTest($account[4])) && p() && e('pass:40;fail:30;'); // 测试获取本年度 admin test18 的用例数