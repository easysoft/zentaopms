#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->loadYaml('action')->gen('100');
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearLogins();
cid=18178
pid=1

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'));

$report = new reportModelTest();

r($report->getUserYearLoginsTest($account[0])) && p() && e('9');  // 测试获取本年度 admin 的登录次数
r($report->getUserYearLoginsTest($account[1])) && p() && e('8');  // 测试获取本年度 dev17 的登录次数
r($report->getUserYearLoginsTest($account[2])) && p() && e('8');  // 测试获取本年度 test18 的登录次数
r($report->getUserYearLoginsTest($account[3])) && p() && e('17'); // 测试获取本年度 admin dev17 的登录次数
r($report->getUserYearLoginsTest($account[4])) && p() && e('17'); // 测试获取本年度 admin test18 的登录次数
