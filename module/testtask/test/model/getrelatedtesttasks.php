#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('testtask')->loadYaml('testtask')->gen(10);

/**

title=测试 testtaskModel->getRelatedTestTasks();
cid=19186
pid=1

*/

global $tester;
$testtask = $tester->loadModel('testtask');

$tasks1 = $testtask->getRelatedTestTasks(1, 1);
$tasks2 = $testtask->getRelatedTestTasks(1, 0);
$tasks3 = $testtask->getRelatedTestTasks(2, 0);

r(count($tasks1)) && p()    && e('3');       //查看产品 1下相关的测试单，排除掉测试单 1，查看数量。
r(count($tasks2)) && p()    && e('4');       //查看产品 1下相关的测试单，不排除任何测试单，查看数量。
r(count($tasks3)) && p()    && e('4');       //查看产品 2下相关的测试单，不排除任何测试单，查看数量。
r($tasks2)        && p('1') && e('测试单1'); //查看产品 1下相关的测试单，不排除任何测试单，查看数量。
r($tasks3)        && p('2') && e('测试单2'); //查看产品 2下相关的测试单，不排除任何测试单，查看测试单名称。
