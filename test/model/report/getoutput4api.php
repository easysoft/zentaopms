#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getOutput4API();
cid=1
pid=1

测试获取 admin 2022 的输出 >> case:70;
测试获取 dev17 2022 的输出 >> bug:1;case:0;
测试获取 test18 2022 的输出 >> productplan:1;case:0;

*/
$account = array('admin', 'dev17', 'test18');
$year    = 2022;

$report = new reportTest();

r($report->getOutput4APITest($account[0], $year)) && p() && e('case:70;');              // 测试获取 admin 2022 的输出
r($report->getOutput4APITest($account[1], $year)) && p() && e('bug:1;case:0;');         // 测试获取 dev17 2022 的输出
r($report->getOutput4APITest($account[2], $year)) && p() && e('productplan:1;case:0;'); // 测试获取 test18 2022 的输出