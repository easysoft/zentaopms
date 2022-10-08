#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getProductTasks();
cid=1
pid=1

查看产品1下的测试单的数量 >> 2
查看系统中所有测试单的数量 >> 11
查看系统中所有截止日期大于2023-01-01的测试单的数量 >> 0
查看ID为11的测试单的详细信息 >> 新增测试报告,system,2022-03-05,2022-09-05
查看ID为3的测试单的详细信息 >> 测试单3,,2022-04-08,2022-04-15

*/

global $tester;
$tester->loadModel('testtask');

$localScope = array('local', 'totalstatus');
$allScope   = array('all', 'totalstatus');

$tasks1 = $tester->testtask->getProductTasks(1, 'all', 'id_desc', null, $localScope);
$tasks2 = $tester->testtask->getProductTasks(2, 'all', 'id_asc', null, $allScope);
$tasks3 = $tester->testtask->getProductTasks(2, 'all', 'id_asc', null, $allScope, '2023-01-01');

r(count($tasks1)) && p()                         && e('2');                                         // 查看产品1下的测试单的数量
r(count($tasks2)) && p()                         && e('11');                                        // 查看系统中所有测试单的数量
r(count($tasks3)) && p()                         && e('0');                                         // 查看系统中所有截止日期大于2023-01-01的测试单的数量
r($tasks1)        && p('11:name,type,begin,end') && e('新增测试报告,system,2022-03-05,2022-09-05'); // 查看ID为11的测试单的详细信息
r($tasks2)        && p('3:name,type,begin,end')  && e('测试单3,,2022-04-08,2022-04-15');            // 查看ID为3的测试单的详细信息