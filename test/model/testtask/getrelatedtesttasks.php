#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getRelatedTestTasks();
cid=1
pid=1

查看产品1下相关的测试单，排除掉测试单1，查看数量 >> 0
查看产品1下相关的测试单，不排除任何测试单，查看数量 >> 1
查看产品2下相关的测试单，不排除任何测试单，查看数量 >> 1
查看产品2下相关的测试单，不排除任何测试单，查看数量 >> 测试单1
查看产品2下相关的测试单，不排除任何测试单，查看数量 >> 测试单2

*/

global $tester;
$tester->loadModel('testtask');

$tasks1 = $tester->testtask->getRelatedTestTasks(1, 1);
$tasks2 = $tester->testtask->getRelatedTestTasks(1, 0);
$tasks3 = $tester->testtask->getRelatedTestTasks(2, 0);

r(count($tasks1)) && p()    && e('0');       //查看产品1下相关的测试单，排除掉测试单1，查看数量
r(count($tasks2)) && p()    && e('1');       //查看产品1下相关的测试单，不排除任何测试单，查看数量
r(count($tasks3)) && p()    && e('1');       //查看产品2下相关的测试单，不排除任何测试单，查看数量
r($tasks2)        && p('1') && e('测试单1'); //查看产品2下相关的测试单，不排除任何测试单，查看数量
r($tasks3)        && p('2') && e('测试单2'); //查看产品2下相关的测试单，不排除任何测试单，查看数量