#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(100);
zdTable('project')->gen(100);
zdTable('project')->config('execution')->gen(300, false);
zdTable('build')->gen(500);
zdTable('testtask')->gen(500);

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

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @5

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的所有状态且某时间段的测试单的数量 @0

- 查询产品ID为1的测试单对应的产品名称第1条的productName属性 @正常产品1

- 查询产品ID为2的测试单对应的产品名称第2条的productName属性 @项目12

- 查询产品ID为1的测试单对应的产品名称第1条的executionName属性 @迭代1

- 查询产品ID为1的测试单对应的产品名称第2条的executionName属性 @项目12/迭代2

*/

global $tester;
$tester->loadModel('testtask');

r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'local,totalstatus'))) && p() && e('5');  // 查询产品ID为1的所有状态的测试单的数量
r(count($tester->testtask->getProductTasks(51, 'all', 'id_desc', null, 'local,totalstatus'))) && p() && e('0');  // 查询产品ID为51的所有状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'local,wait')))        && p() && e('5');  // 查询产品ID为1的等待状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'local,done')))        && p() && e('0');  // 查询产品ID为1的完成状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'local,blocked')))     && p() && e('0');  // 查询产品ID为1的阻塞状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'all,totalstatus')))   && p() && e('150'); // 查询有权限产品的所有状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'all,wait')))          && p() && e('45'); // 查询有权限产品的等待状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'all,done')))          && p() && e('30'); // 查询有权限产品的完成状态的测试单的数量
r(count($tester->testtask->getProductTasks(1,  'all', 'id_desc', null, 'all,blocked')))       && p() && e('30'); // 查询有权限产品的阻塞状态的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d'))))                      && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('-1 day'))))) && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('+1 day'))))) && p() && e('0');  // 查询产品ID为1的所有状态且某时间段的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', '', date('Y-m-d')))) && p() && e('0');                       // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', '', date('Y-m-d', strtotime('+6 day'))))) && p() && e('0');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', '', date('Y-m-d', strtotime('+7 day'))))) && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', '', date('Y-m-d', strtotime('+8 day'))))) && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d'), date('Y-m-d')))) && p() && e('0');                       // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d'), date('Y-m-d', strtotime('+6 day'))))) && p() && e('0');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d'), date('Y-m-d', strtotime('+7 day'))))) && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d'), date('Y-m-d', strtotime('+8 day'))))) && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('-1 day')), date('Y-m-d')))) && p() && e('0');                       // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('+6 day'))))) && p() && e('0');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('+7 day'))))) && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('+8 day'))))) && p() && e('5');  // 查询产品ID为1的所有状态且某时间段的测试单的数量

r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('+1 day')), date('Y-m-d')))) && p() && e('0');                       // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+6 day'))))) && p() && e('0');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+7 day'))))) && p() && e('0');  // 查询产品ID为1的所有状态且某时间段的测试单的数量
r(count($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus', date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+8 day'))))) && p() && e('0');  // 查询产品ID为1的所有状态且某时间段的测试单的数量

r($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus')) && p('1:productName') && e('正常产品1');      // 查询产品ID为1、测试单ID为1的测试单对应的执行名称
r($tester->testtask->getProductTasks(2, 'all', 'id_desc', null, 'local,totalstatus')) && p('2:productName') && e('项目12');         // 查询产品ID为2、测试单ID为2的测试单对应的执行名称
r($tester->testtask->getProductTasks(1, 'all', 'id_desc', null, 'local,totalstatus')) && p('1:executionName') && e('迭代1');        // 查询产品ID为1、测试单ID为1的测试单对应的执行名称
r($tester->testtask->getProductTasks(2, 'all', 'id_desc', null, 'local,totalstatus')) && p('2:executionName') && e('项目12/迭代2'); // 查询产品ID为1、测试单ID为2的测试单对应的执行名称

