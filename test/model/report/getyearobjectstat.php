#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getYearObjectStat();
cid=1
pid=1

测试获取 admin story >> active:1;
测试获取 admin task >> 0
测试获取 admin bug >> 0
测试获取 dev17 story >> 0
测试获取 dev17 task >> 0
测试获取 dev17 bug >> active:1;
测试获取 test18 story >> 0
测试获取 test18 task >> 0
测试获取 test18 bug >> 0
测试获取 admin dev17 story >> active:1;
测试获取 admin dev17 task >> 0
测试获取 admin dev17 bug >> active:1;
测试获取 admin test18 story >> active:1;
测试获取 admin test18 task >> 0
测试获取 admin test18 bug >> 0

*/
$accounts = array('admin', 'dev17', 'test18', 'admin,dev17', 'admin,test18');
$objectType = array('story', 'task', 'bug');

$report = new reportTest();

r($report->getYearObjectStatTest($accounts[0], $objectType[0])) && p() && e('active:1;'); // 测试获取 admin story
r($report->getYearObjectStatTest($accounts[0], $objectType[1])) && p() && e('0');         // 测试获取 admin task
r($report->getYearObjectStatTest($accounts[0], $objectType[2])) && p() && e('0');         // 测试获取 admin bug
r($report->getYearObjectStatTest($accounts[1], $objectType[0])) && p() && e('0');         // 测试获取 dev17 story
r($report->getYearObjectStatTest($accounts[1], $objectType[1])) && p() && e('0');         // 测试获取 dev17 task
r($report->getYearObjectStatTest($accounts[1], $objectType[2])) && p() && e('active:1;'); // 测试获取 dev17 bug
r($report->getYearObjectStatTest($accounts[2], $objectType[0])) && p() && e('0');         // 测试获取 test18 story
r($report->getYearObjectStatTest($accounts[2], $objectType[1])) && p() && e('0');         // 测试获取 test18 task
r($report->getYearObjectStatTest($accounts[2], $objectType[2])) && p() && e('0');         // 测试获取 test18 bug
r($report->getYearObjectStatTest($accounts[3], $objectType[0])) && p() && e('active:1;'); // 测试获取 admin dev17 story
r($report->getYearObjectStatTest($accounts[3], $objectType[1])) && p() && e('0');         // 测试获取 admin dev17 task
r($report->getYearObjectStatTest($accounts[3], $objectType[2])) && p() && e('active:1;'); // 测试获取 admin dev17 bug
r($report->getYearObjectStatTest($accounts[4], $objectType[0])) && p() && e('active:1;'); // 测试获取 admin test18 story
r($report->getYearObjectStatTest($accounts[4], $objectType[1])) && p() && e('0');         // 测试获取 admin test18 task
r($report->getYearObjectStatTest($accounts[4], $objectType[2])) && p() && e('0');         // 测试获取 admin test18 bug