#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('action')->loadYaml('action')->gen('100');
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearContributionCount();
cid=1
pid=1

测试获取本年度 admin 的贡献数 >> 25
测试获取本年度 dev17 的贡献数 >> 25
测试获取本年度 test18 的贡献数 >> 25
测试获取本年度 admin dev17 的贡献数 >> 50
测试获取本年度 admin test18 的贡献数 >> 50
测试获取本年度 所有用户 的贡献数 >> 75

*/
$accounts = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportTest();

r($report->getUserYearContributionCountTest($accounts[0], date('Y'))) && p() && e('25');  // 测试获取本年度 admin 的贡献数
r($report->getUserYearContributionCountTest($accounts[1], date('Y'))) && p() && e('25');  // 测试获取本年度 dev17 的贡献数
r($report->getUserYearContributionCountTest($accounts[2], date('Y'))) && p() && e('25');  // 测试获取本年度 test18 的贡献数
r($report->getUserYearContributionCountTest($accounts[3], date('Y'))) && p() && e('50');  // 测试获取本年度 admin dev17 的贡献数
r($report->getUserYearContributionCountTest($accounts[4], date('Y'))) && p() && e('50');  // 测试获取本年度 admin test18 的贡献数
r($report->getUserYearContributionCountTest($accounts[5], date('Y'))) && p() && e('75'); // 测试获取本年度 所有用户 的贡献数