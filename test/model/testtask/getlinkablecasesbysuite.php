#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->getLinkableCasesBySuite();
cid=1
pid=1

根据测试套件1获取测试单1可关联的用例数量 >> 1
根据测试套件2获取测试单2可关联的用例数量 >> 1
根据测试套件1获取测试单1可关联的用例详情 >> 2,这个是测试用例2,normal,performance
根据测试套件2获取测试单2可关联的用例详情 >> 6,这个是测试用例6,normal,interface

*/

global $tester;
$tester->loadModel('testtask');
$tester->app->loadClass('pager', $static = true);

$task1 = $tester->testtask->getById(1);
$task2 = $tester->testtask->getById(2);
$pager = new pager(0, 10, 1);

$cases1 = $tester->testtask->getLinkableCasesBySuite(1, $task1, '1 = 1', 1, '', $pager);
$cases2 = $tester->testtask->getLinkableCasesBySuite(2, $task2, '1 = 1', 3, '', $pager);

r(count($cases1)) && p()                         && e('1'); // 根据测试套件1获取测试单1可关联的用例数量
r(count($cases2)) && p()                         && e('1'); // 根据测试套件2获取测试单2可关联的用例数量
r($cases1)        && p('0:id,title,status,type') && e('2,这个是测试用例2,normal,performance'); // 根据测试套件1获取测试单1可关联的用例详情
r($cases2)        && p('0:id,title,status,type') && e('6,这个是测试用例6,normal,interface');   // 根据测试套件2获取测试单2可关联的用例详情