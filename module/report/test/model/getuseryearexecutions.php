#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('execution')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('bug')->gen(20);
zenData('build')->gen(20);
zenData('team')->loadYaml('team')->gen(20);
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearExecutions();
cid=18177
pid=1

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportModelTest();

r($report->getUserYearExecutionsTest($account[0])) && p() && e('0');           // 测试获取本年度 admin 的执行id
r($report->getUserYearExecutionsTest($account[1])) && p() && e('114,111,103'); // 测试获取本年度 dev17 的执行id
r($report->getUserYearExecutionsTest($account[2])) && p() && e('112');         // 测试获取本年度 test18 的执行id
r($report->getUserYearExecutionsTest($account[3])) && p() && e('114,111,103'); // 测试获取本年度 admin dev17 的执行id
r($report->getUserYearExecutionsTest($account[4])) && p() && e('112');         // 测试获取本年度 admin test18 的执行id
r($report->getUserYearExecutionsTest($account[5])) && p() && e('116,115,114,113,112,111,110,109,108,107,106,103,102,101'); // 测试获取本年度 所有用户 的执行id
