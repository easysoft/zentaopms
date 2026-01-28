#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->loadYaml('action')->gen('100');
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearActions();
cid=18173
pid=1

测试获取本年度 admin 的操作数 >> 34
测试获取本年度 dev17 的操作数 >> 33
测试获取本年度 test18 的操作数 >> 33
测试获取本年度 admin dev17 的操作数 >> 67
测试获取本年度 admin test18 的操作数 >> 67
测试获取本年度 所有用户 的操作数 >> 100

*/
$accounts = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportModelTest();

r($report->getUserYearActionsTest($accounts[0])) && p() && e('34');  // 测试获取本年度 admin 的操作数
r($report->getUserYearActionsTest($accounts[1])) && p() && e('33');  // 测试获取本年度 dev17 的操作数
r($report->getUserYearActionsTest($accounts[2])) && p() && e('33');  // 测试获取本年度 test18 的操作数
r($report->getUserYearActionsTest($accounts[3])) && p() && e('67');  // 测试获取本年度 admin dev17 的操作数
r($report->getUserYearActionsTest($accounts[4])) && p() && e('67');  // 测试获取本年度 admin test18 的操作数
r($report->getUserYearActionsTest($accounts[5])) && p() && e('100'); // 测试获取本年度 所有用户 的操作数
