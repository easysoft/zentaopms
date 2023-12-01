#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('action')->config('action')->gen('100');
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearLogins();
cid=1
pid=1

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'));

$report = new reportTest();

r($report->getUserYearLoginsTest($account[0])) && p() && e('9');  // 测试获取本年度 admin 的登录次数
r($report->getUserYearLoginsTest($account[1])) && p() && e('8');  // 测试获取本年度 dev17 的登录次数
r($report->getUserYearLoginsTest($account[2])) && p() && e('8');  // 测试获取本年度 test18 的登录次数
r($report->getUserYearLoginsTest($account[3])) && p() && e('17'); // 测试获取本年度 admin dev17 的登录次数
r($report->getUserYearLoginsTest($account[4])) && p() && e('17'); // 测试获取本年度 admin test18 的登录次数
