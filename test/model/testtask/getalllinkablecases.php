#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getAllLinkableCases();
cid=1
pid=1

获取可关联到测试单1的用例列表，每页10条，查看数量 >> 10
获取可关联到测试单2的用例列表，查看数量 >> 307
获取可关联到测试单2的用例列表，传入已关联的用例ID，查看数量 >> 305
获取可关联到测试单2的用例列表，传入sql语句，查看数量 >> 74

*/

global $tester;
$tester->loadModel('testtask');
$tester->app->loadClass('pager', $static = true);

$task1 = $tester->testtask->getById(1);
$task2 = $tester->testtask->getById(2);
$pager = new pager(0, 10, 1);

$cases1 = $tester->testtask->getAllLinkableCases($task1, '1 = 1', '', $pager);
$cases2 = $tester->testtask->getAllLinkableCases($task2, '1 = 1', '', null);
$cases3 = $tester->testtask->getAllLinkableCases($task2, '1 = 1', '410,408', null);
$cases4 = $tester->testtask->getAllLinkableCases($task2, 'id < 100', '410,408', null);

r(count($cases1)) && p() && e('10');  // 获取可关联到测试单1的用例列表，每页10条，查看数量
r(count($cases2)) && p() && e('307'); // 获取可关联到测试单2的用例列表，查看数量
r(count($cases3)) && p() && e('305'); // 获取可关联到测试单2的用例列表，传入已关联的用例ID，查看数量
r(count($cases4)) && p() && e('74');  // 获取可关联到测试单2的用例列表，传入sql语句，查看数量