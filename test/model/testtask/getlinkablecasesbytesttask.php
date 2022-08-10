#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->getLinkableCasesByTestTask();
cid=1
pid=1

直接获取测试单1可关联的用例数量,排除掉ID为2的用例，查看数量 >> 2
直接获取测试单2可关联的用例数量,查看数量 >> 3
直接获取测试单3可关联的用例数量,查看数量 >> 3
直接获取测试单1可关联的用例数量,排除掉ID为2的用例，查看获取到的第一个用例的详情 >> 3,这个是测试用例3,config,intergrate
直接获取测试单2可关联的用例数量,查看获取到的第一个用例的详情 >> 6,这个是测试用例6,interface,bvt
直接获取测试单3可关联的用例数量,查看获取到的第一个用例的详情 >> 10,这个是测试用例10,config,system

*/

global $tester;
$tester->loadModel('testtask');
$tester->app->loadClass('pager', $static = true);

$task1 = $tester->testtask->getById(1);
$task2 = $tester->testtask->getById(2);
$pager = new pager(0, 10, 1);

$cases1 = $tester->testtask->getLinkableCasesByTestTask(1, array(2), '1 = 1', $pager);
$cases2 = $tester->testtask->getLinkableCasesByTestTask(2, '', '1 = 1', $pager);
$cases3 = $tester->testtask->getLinkableCasesByTestTask(3, '', '1 = 1', $pager);

r(count($cases1)) && p()                        && e('2'); // 直接获取测试单1可关联的用例数量,排除掉ID为2的用例，查看数量
r(count($cases2)) && p()                        && e('3'); // 直接获取测试单2可关联的用例数量,查看数量
r(count($cases3)) && p()                        && e('3'); // 直接获取测试单3可关联的用例数量,查看数量
r($cases1)        && p('0:id,title,type,stage') && e('3,这个是测试用例3,config,intergrate'); // 直接获取测试单1可关联的用例数量,排除掉ID为2的用例，查看获取到的第一个用例的详情
r($cases2)        && p('0:id,title,type,stage') && e('6,这个是测试用例6,interface,bvt');     // 直接获取测试单2可关联的用例数量,查看获取到的第一个用例的详情
r($cases3)        && p('0:id,title,type,stage') && e('10,这个是测试用例10,config,system');   // 直接获取测试单3可关联的用例数量,查看获取到的第一个用例的详情