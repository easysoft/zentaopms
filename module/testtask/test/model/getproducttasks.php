#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('group')->gen(0);
zdTable('userview')->gen(0);
zdTable('product')->config('product')->gen(100);
zdTable('project')->config('project')->gen(0);
zdTable('project')->config('project')->gen(100);
zdTable('project')->config('execution')->gen(300, false, false);
zdTable('build')->gen(500);
zdTable('testtask')->gen(500);

su('admin');

/**

title=测试 testtaskModel->getProductTasks();
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

- 查询产品ID为1、测试单ID为1的测试单对应的产品名称第1条的productName属性 @正常产品1

- 查询产品ID为2、测试单ID为2的测试单对应的产品名称第2条的productName属性 @项目12

- 查询产品ID为1、测试单ID为1的测试单对应的执行名称第1条的executionName属性 @迭代1

- 查询产品ID为1、测试单ID为2的测试单对应的执行名称第2条的executionName属性 @项目12/迭代2

- 验证产品ID为1，索引为201数据的所有字段
 - 第201条的id属性 @201
 - 第201条的project属性 @11
 - 第201条的name属性 @测试单1
 - 第201条的product属性 @1
 - 第201条的execution属性 @101
 - 第201条的build属性 @11
 - 第201条的owner属性 @user11
 - 第201条的pri属性 @1
 - 第201条的desc属性 @这是测试单描述201
 - 第201条的status属性 @wait
 - 第201条的testreport属性 @0
 - 第201条的auto属性 @no
 - 第201条的deleted属性 @0

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

r(count($tester->testtask->getProductTasks(1,  'all', 'local,totalstatus'))) && p() && e('5');   // 查询产品ID为1的所有状态的测试单的数量
r(count($tester->testtask->getProductTasks(51, 'all', 'local,totalstatus'))) && p() && e('0');   // 查询产品ID为51的所有状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'local,wait')))        && p() && e('5');   // 查询产品ID为1的等待状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'local,done')))        && p() && e('0');   // 查询产品ID为1的完成状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'local,blocked')))     && p() && e('0');   // 查询产品ID为1的阻塞状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'all,totalstatus')))   && p() && e('250'); // 查询有权限产品的所有状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'all,wait')))          && p() && e('65');  // 查询有权限产品的等待状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'all,done')))          && p() && e('60');  // 查询有权限产品的完成状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'all,blocked')))       && p() && e('60');  // 查询有权限产品的阻塞状态的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $today)))       && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在今天之后的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $yesterday)))   && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在昨天之后的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $oneDayLater))) && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', '', $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且结束时间在今天之内的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', '', $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且结束时间在6天后之内的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', '', $sevenDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且结束时间在7天后之内的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', '', $eightDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且结束时间在7天后之内的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $today, $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在今天之内的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $today, $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在6天后之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $today, $sevenDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在7天后之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $today, $eightDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在今天之后结束时间在8天后之内的的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $yesterday, $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在今天之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $yesterday, $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在6天后之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $yesterday, $sevenDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在7天后之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $yesterday, $eightDaysLater))) && p() && e('5');  // 查询产品ID为1的所有状态且开始时间在昨天之后结束时间在8天后之内的的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $oneDayLater, $today)))          && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在今天之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $oneDayLater, $sixDaysLater)))   && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在6天后之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $oneDayLater, $sevenDaysLater))) && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在7天后之内的的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus', $oneDayLater, $eightDaysLater))) && p() && e('0');  // 查询产品ID为1的所有状态且开始时间在明天之后结束时间在8天后之内的的测试单的数量

r($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus')) && p('1:productName') && e('正常产品1');      // 查询产品ID为1、测试单ID为1的测试单对应的产品名称
r($tester->testtask->getProductTasks(2, 'all', 'local,totalstatus')) && p('2:productName') && e('项目12');         // 查询产品ID为2、测试单ID为2的测试单对应的产品名称
r($tester->testtask->getProductTasks(1, 'all', 'local,totalstatus')) && p('1:executionName') && e('迭代1');        // 查询产品ID为1、测试单ID为1的测试单对应的执行名称
r($tester->testtask->getProductTasks(2, 'all', 'local,totalstatus')) && p('2:executionName') && e('项目12/迭代2'); // 查询产品ID为1、测试单ID为2的测试单对应的执行名称

r($tester->testtask->getProductTasks(1)) && p('201:id,project,name,product,execution,build,owner,pri,desc,status,testreport,auto,deleted') && e('201,11,测试单1,1,101,11,user11,1,这是测试单描述201,wait,0,no,0'); // 验证产品ID为1，索引为201数据的所有字段
