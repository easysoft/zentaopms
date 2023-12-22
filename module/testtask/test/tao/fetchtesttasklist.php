#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('group')->gen(0);
zdTable('userview')->gen(0);
zdTable('product')->config('product')->gen(100);
zdTable('project')->config('project')->gen(100);
zdTable('project')->config('execution')->gen(300, false);
zdTable('build')->gen(500);
zdTable('testtask')->gen(500);

su('admin');

/**

title=测试 testtaskModel->fetchTesttaskList();
timeout=0
cid=1

- 查询产品ID为1的所有状态的测试单的数量 @5

- 查询产品ID为51的所有状态的测试单的数量 @0

- 查询产品ID为1的等待状态的测试单的数量 @5

- 查询产品ID为1的完成状态的测试单的数量 @0

- 查询产品ID为1的阻塞状态的测试单的数量 @0

- 查询有权限产品的所有状态的测试单的数量 @150

- 查询有权限产品的等待状态的测试单的数量 @45

- 查询有权限产品的完成状态的测试单的数量 @30

- 查询有权限产品的阻塞状态的测试单的数量 @30

- 查询产品ID为1、项目ID为11的所有状态的测试单的数量 @5

- 查询产品ID为1、项目ID为11的等待状态的测试单的数量 @5

- 查询产品ID为1、项目ID为11的完成状态的测试单的数量 @0

- 查询产品ID为1、项目ID为11的阻塞状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的所有状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的等待状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的完成状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的阻塞状态的测试单的数量 @0

- 查询有权限产品、项目ID为11的所有状态的测试单的数量 @5

- 查询有权限产品、项目ID为11的等待状态的测试单的数量 @5

- 查询有权限产品、项目ID为11的完成状态的测试单的数量 @0

- 查询有权限产品、项目ID为11的阻塞状态的测试单的数量 @0

- 查询有权限产品、项目ID为12的所有状态的测试单的数量 @5

- 查询有权限产品、项目ID为12的等待状态的测试单的数量 @0

- 查询有权限产品、项目ID为12的完成状态的测试单的数量 @0

- 查询有权限产品、项目ID为12的阻塞状态的测试单的数量 @0

- 查询产品ID为1的所有状态的单元测试单的数量 @5

- 查询产品ID为51的所有状态的单元测试单的数量 @0

- 查询产品ID为1的等待状态的单元测试单的数量 @0

- 查询产品ID为1的完成状态的单元测试单的数量 @5

- 查询产品ID为1的阻塞状态的单元测试单的数量 @0

- 查询有权限产品的所有状态的单元测试单的数量 @150

- 查询有权限产品的等待状态的单元测试单的数量 @30

- 查询有权限产品的完成状态的单元测试单的数量 @45

- 查询有权限产品的阻塞状态的单元测试单的数量 @45

- 查询产品ID为1、项目ID为11的所有状态的测试单的数量 @5

- 查询产品ID为1、项目ID为11的等待状态的测试单的数量 @0

- 查询产品ID为1、项目ID为11的完成状态的测试单的数量 @5

- 查询产品ID为1、项目ID为11的阻塞状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的所有状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的等待状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的完成状态的测试单的数量 @0

- 查询产品ID为1、项目ID为12的阻塞状态的测试单的数量 @0

- 查询有权限产品、项目ID为11的所有状态的测试单的数量 @5

- 查询有权限产品、项目ID为11的等待状态的测试单的数量 @0

- 查询有权限产品、项目ID为11的完成状态的测试单的数量 @5

- 查询有权限产品、项目ID为11的阻塞状态的测试单的数量 @0

- 查询有权限产品、项目ID为12的所有状态的测试单的数量 @5

- 查询有权限产品、项目ID为12的等待状态的测试单的数量 @0

- 查询有权限产品、项目ID为12的完成状态的测试单的数量 @0

- 查询有权限产品、项目ID为12的阻塞状态的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在今天之后的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在昨天之后的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在明天之后的测试单的数量 @0

- 查询产品ID为1的所有状态且结束时间在今天之内的测试单的数量 @0

- 查询产品ID为1的所有状态且结束时间在6天后之内的测试单的数量 @0

- 查询产品ID为1的所有状态且结束时间在7天后之内的测试单的数量 @5

- 查询产品ID为1的所有状态且结束时间在7天后之内的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在今天之后结束时间在今天之内的测试单的数量 @0

- 查询产品ID为1的所有状态且开始时间在今天之后结束时间在6天后之内的的测试单的数量 @0

- 查询产品ID为1的所有状态且开始时间在今天之后结束时间在7天后之内的的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在今天之后结束时间在8天后之内的的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在今天之内的的测试单的数量 @0

- 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在6天后之内的的测试单的数量 @0

- 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在7天后之内的的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在8天后之内的的测试单的数量 @5

- 查询产品ID为1的所有状态且开始时间在明天之后结束时间在今天之内的的测试单的数量 @0

- 查询产品ID为1的所有状态且开始时间在明天之后结束时间在6天后之内的的测试单的数量 @0

- 查询产品ID为1的所有状态且开始时间在明天之后结束时间在7天后之内的的测试单的数量 @0

- 查询产品ID为1的所有状态且开始时间在明天之后结束时间在8天后之内的的测试单的数量 @0

*/

global $tester;
$tester->loadModel('testtask');

$localTotal     = 'local,totalstatus';
$yesterday      = date('Y-m-d', strtotime('-1 day'));
$today          = date('Y-m-d');
$oneDayLater    = date('Y-m-d', strtotime('+1 day'));
$sixDaysLater   = date('Y-m-d', strtotime('+6 day'));
$sevenDaysLater = date('Y-m-d', strtotime('+7 day'));
$eightDaysLater = date('Y-m-d', strtotime('+8 day'));

r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'local', 'totalstatus'))) && p() && e('5');   // 查询产品ID为1的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(51, 'all', 0, 'no', 'local', 'totalstatus'))) && p() && e('0');   // 查询产品ID为51的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'local', 'wait')))        && p() && e('5');   // 查询产品ID为1的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'local', 'done')))        && p() && e('0');   // 查询产品ID为1的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'local', 'blocked')))     && p() && e('0');   // 查询产品ID为1的阻塞状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'all', 'totalstatus')))   && p() && e('250'); // 查询有权限产品的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'all', 'wait')))          && p() && e('65');  // 查询有权限产品的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'all', 'done')))          && p() && e('60');  // 查询有权限产品的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'no', 'all', 'blocked')))       && p() && e('60');  // 查询有权限产品的阻塞状态的测试单的数量

r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'local', 'totalstatus'))) && p() && e('5'); // 查询产品ID为1、项目ID为11的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'local', 'wait')))        && p() && e('5'); // 查询产品ID为1、项目ID为11的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'local', 'done')))        && p() && e('0'); // 查询产品ID为1、项目ID为11的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'local', 'blocked')))     && p() && e('0'); // 查询产品ID为1、项目ID为11的阻塞状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'local', 'totalstatus'))) && p() && e('0'); // 查询产品ID为1、项目ID为12的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'local', 'wait')))        && p() && e('0'); // 查询产品ID为1、项目ID为12的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'local', 'done')))        && p() && e('0'); // 查询产品ID为1、项目ID为12的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'local', 'blocked')))     && p() && e('0'); // 查询产品ID为1、项目ID为12的阻塞状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'all', 'totalstatus')))   && p() && e('5'); // 查询有权限产品、项目ID为11的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'all', 'wait')))          && p() && e('5'); // 查询有权限产品、项目ID为11的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'all', 'done')))          && p() && e('0'); // 查询有权限产品、项目ID为11的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'no', 'all', 'blocked')))       && p() && e('0'); // 查询有权限产品、项目ID为11的阻塞状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'all', 'totalstatus')))   && p() && e('5'); // 查询有权限产品、项目ID为12的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'all', 'wait')))          && p() && e('0'); // 查询有权限产品、项目ID为12的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'all', 'done')))          && p() && e('0'); // 查询有权限产品、项目ID为12的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'no', 'all', 'blocked')))       && p() && e('0'); // 查询有权限产品、项目ID为12的阻塞状态的测试单的数量

r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'local', 'totalstatus'))) && p() && e('5');   // 查询产品ID为1的所有状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(51, 'all', 0, 'unit', 'local', 'totalstatus'))) && p() && e('0');   // 查询产品ID为51的所有状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'local', 'wait')))        && p() && e('0');   // 查询产品ID为1的等待状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'local', 'done')))        && p() && e('5');   // 查询产品ID为1的完成状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'local', 'blocked')))     && p() && e('0');   // 查询产品ID为1的阻塞状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'all', 'totalstatus')))   && p() && e('250'); // 查询有权限产品的所有状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'all', 'wait')))          && p() && e('60');  // 查询有权限产品的等待状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'all', 'done')))          && p() && e('65');  // 查询有权限产品的完成状态的单元测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 0, 'unit', 'all', 'blocked')))       && p() && e('65');  // 查询有权限产品的阻塞状态的单元测试单的数量

r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'local', 'totalstatus'))) && p() && e('5');  // 查询产品ID为1、项目ID为11的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'local', 'wait')))        && p() && e('0');  // 查询产品ID为1、项目ID为11的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'local', 'done')))        && p() && e('5');  // 查询产品ID为1、项目ID为11的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'local', 'blocked')))     && p() && e('0');  // 查询产品ID为1、项目ID为11的阻塞状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'local', 'totalstatus'))) && p() && e('0');  // 查询产品ID为1、项目ID为12的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'local', 'wait')))        && p() && e('0');  // 查询产品ID为1、项目ID为12的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'local', 'done')))        && p() && e('0');  // 查询产品ID为1、项目ID为12的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'local', 'blocked')))     && p() && e('0');  // 查询产品ID为1、项目ID为12的阻塞状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'all', 'totalstatus')))   && p() && e('5');  // 查询有权限产品、项目ID为11的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'all', 'wait')))          && p() && e('0');  // 查询有权限产品、项目ID为11的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'all', 'done')))          && p() && e('5');  // 查询有权限产品、项目ID为11的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 11, 'unit', 'all', 'blocked')))       && p() && e('0');  // 查询有权限产品、项目ID为11的阻塞状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'all', 'totalstatus')))   && p() && e('5');  // 查询有权限产品、项目ID为12的所有状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'all', 'wait')))          && p() && e('0');  // 查询有权限产品、项目ID为12的等待状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'all', 'done')))          && p() && e('0');  // 查询有权限产品、项目ID为12的完成状态的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1,  'all', 12, 'unit', 'all', 'blocked')))       && p() && e('5');  // 查询有权限产品、项目ID为12的阻塞状态的测试单的数量

r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $today)))       && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在今天之后的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $yesterday)))   && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在昨天之后的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $oneDayLater))) && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后的测试单的数量

r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', '', $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且结束时间在今天之内的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', '', $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且结束时间在6天后之内的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', '', $sevenDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且结束时间在7天后之内的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', '', $eightDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且结束时间在7天后之内的测试单的数量

r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $today, $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在今天之内的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $today, $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在6天后之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $today, $sevenDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在7天后之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $today, $eightDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在8天后之内的的测试单的数量

r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $yesterday, $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在今天之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $yesterday, $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在6天后之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $yesterday, $sevenDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在7天后之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $yesterday, $eightDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在8天后之内的的测试单的数量

r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $oneDayLater, $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在今天之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $oneDayLater, $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在6天后之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $oneDayLater, $sevenDaysLater))) && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在7天后之内的的测试单的数量
r(count($tester->testtask->fetchTesttaskList(1, 'all', 0, 'no', 'local', 'totalstatus', $oneDayLater, $eightDaysLater))) && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在8天后之内的的测试单的数量

r($tester->testtask->fetchTesttaskList(1)) && p('201:id,project,name,product,execution,build,owner,pri,desc,status,testreport,auto,deleted') && e('201,11,测试单1,1,101,11,user11,1,这是测试单描述201,wait,0,no,0'); // 验证产品ID为1，索引为201数据的所有字段
