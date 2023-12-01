#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('project')->config('execution')->gen(20);
zdTable('task')->config('task')->gen(20);
zdTable('bug')->gen(20);
zdTable('build')->gen(20);
zdTable('team')->config('team')->gen(20);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearExecutions();
cid=1
pid=1

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportTest();

r($report->getUserYearExecutionsTest($account[0])) && p() && e('0');           // 测试获取本年度 admin 的执行id
r($report->getUserYearExecutionsTest($account[1])) && p() && e('114,111,103'); // 测试获取本年度 dev17 的执行id
r($report->getUserYearExecutionsTest($account[2])) && p() && e('112');         // 测试获取本年度 test18 的执行id
r($report->getUserYearExecutionsTest($account[3])) && p() && e('114,111,103'); // 测试获取本年度 admin dev17 的执行id
r($report->getUserYearExecutionsTest($account[4])) && p() && e('112');         // 测试获取本年度 admin test18 的执行id
r($report->getUserYearExecutionsTest($account[5])) && p() && e('116,115,114,113,112,111,110,109,108,107,106,103,102,101'); // 测试获取本年度 所有用户 的执行id
