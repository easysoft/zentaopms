#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('action')->config('action_annual')->gen(200);
zdTable('case')->gen(80);
zdTable('testresult')->config('testresult')->gen(80);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getYearCaseStat();
cid=1
pid=1

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportTest();

r($report->getYearCaseStatTest($account[0])) && p() && e('pass:1;');          // 测试获取本年度 admin 的用例数
r($report->getYearCaseStatTest($account[1])) && p() && e('pass:1;fail:2;');   // 测试获取本年度 dev17 的用例数
r($report->getYearCaseStatTest($account[2])) && p() && e('pass:2;fail:1;');   // 测试获取本年度 test18 的用例数
r($report->getYearCaseStatTest($account[3])) && p() && e('pass:2;fail:2;');   // 测试获取本年度 admin dev17 的用例数
r($report->getYearCaseStatTest($account[4])) && p() && e('pass:3;fail:1;');   // 测试获取本年度 admin test18 的用例数
r($report->getYearCaseStatTest($account[5])) && p() && e('pass:27;fail:23;'); // 测试获取本年度 所有用户 的用例数
