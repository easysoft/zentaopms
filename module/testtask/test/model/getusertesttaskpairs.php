#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('testtask')->loadYaml('testtask')->gen(10);
zenData('project')->loadYaml('project')->gen(2);
zenData('product')->loadYaml('product')->gen(2);

/**

title=测试 testtaskModel->getUserTestTaskPairs();
cid=19197
pid=1

*/

global $tester;
$testtask = $tester->loadModel('testtask');

$tasks = $testtask->getUserTestTaskPairs('user1');
r(count($tasks)) && p() && e(3);             // 查看用户 1 负责的测试单数量。
r($tasks) && p('1') && e('执行1 / 测试单1'); // 查看用户 1 负责的第 1 个测试单。
r($tasks) && p('2') && e('执行2 / 测试单2'); // 查看用户 1 负责的第 2 个测试单。
r($tasks) && p('6') && e('执行2 / 测试单6'); // 查看用户 1 负责的第 3 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user2');
r(count($tasks)) && p() && e(4);             // 查看用户 2 负责的测试单数量。
r($tasks) && p('3') && e('执行1 / 测试单3'); // 查看用户 2 负责的第 1 个测试单。
r($tasks) && p('4') && e('执行2 / 测试单4'); // 查看用户 2 负责的第 2 个测试单。
r($tasks) && p('7') && e('执行1 / 测试单7'); // 查看用户 2 负责的第 3 个测试单。
r($tasks) && p('8') && e('执行2 / 测试单8'); // 查看用户 2 负责的第 4 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 1);
r(count($tasks)) && p() && e(1);             // 查看用户 1 负责的测试单限制查询 1 个后的数量。
r($tasks) && p('1') && e('执行1 / 测试单1'); // 查看用户 1 负责的测试单限制查询 1 个后的第 1 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, '');
r(count($tasks)) && p() && e(0); // 查看用户 1 负责的测试单中状态为空的数量。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, 'wait');
r(count($tasks)) && p() && e(1);             // 查看用户 1 负责的测试单中状态为未开始的数量。
r($tasks) && p('1') && e('执行1 / 测试单1'); // 查看用户 1 负责的测试单中状态为未开始的第 2 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, 'doing');
r(count($tasks)) && p() && e(2);             // 查看用户 1 负责的测试单中状态为进行中的数量。
r($tasks) && p('2') && e('执行2 / 测试单2'); // 查看用户 1 负责的测试单中状态为进行中的第 1 个测试单。
r($tasks) && p('6') && e('执行2 / 测试单6'); // 查看用户 1 负责的测试单中状态为进行中的第 2 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 1, 'doing');
r(count($tasks)) && p() && e(1);             // 查看用户 1 负责的测试单中状态为进行中并限制查询 1 个后的数量。
r($tasks) && p('2') && e('执行2 / 测试单2'); // 查看用户 1 负责的测试单中状态为进行中并限制查询 1 个后的第 1 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, 'all');
r(count($tasks)) && p() && e(3);             // 查看用户 1 负责的测试单中状态为全部的数量。
r($tasks) && p('1') && e('执行1 / 测试单1'); // 查看用户 1 负责的测试单中状态为全部的第 1 个测试单。
r($tasks) && p('2') && e('执行2 / 测试单2'); // 查看用户 1 负责的测试单中状态为全部的第 2 个测试单。
r($tasks) && p('6') && e('执行2 / 测试单6'); // 查看用户 1 负责的测试单中状态为全部的第 3 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, 'all', array(1));
r(count($tasks)) && p() && e(0); // 查看用户 1 负责的测试单中排除掉产品 1 的测试单后的数量。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, 'all', array(2));
r(count($tasks)) && p() && e(3);             // 查看用户 1 负责的测试单中排除掉产品 2 的测试单后的数量。
r($tasks) && p('1') && e('执行1 / 测试单1'); // 查看用户 1 负责的测试单中排除掉产品 2 的测试单后的第 1 个测试单。
r($tasks) && p('2') && e('执行2 / 测试单2'); // 查看用户 1 负责的测试单中排除掉产品 2 的测试单后的第 2 个测试单。
r($tasks) && p('6') && e('执行2 / 测试单6'); // 查看用户 1 负责的测试单中排除掉产品 2 的测试单后的第 3 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 1, 'all', array(2));
r(count($tasks)) && p() && e(1);             // 查看用户 1 负责的测试单中排除掉产品 2 的测试单并限制查询 1 个后的数量。
r($tasks) && p('1') && e('执行1 / 测试单1'); // 查看用户 1 负责的测试单中排除掉产品 2 的测试单并限制查询 1 个后的第 1 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, 'all', array(), array(1));
r(count($tasks)) && p() && e(2);             // 查看用户 1 负责的测试单中排除掉执行 1 的测试单后的数量。
r($tasks) && p('2') && e('执行2 / 测试单2'); // 查看用户 1 负责的测试单中排除掉执行 1 的测试单后的第 1 个测试单。
r($tasks) && p('6') && e('执行2 / 测试单6'); // 查看用户 1 负责的测试单中排除掉执行 1 的测试单后的第 2 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 1, 'all', array(), array(1));
r(count($tasks)) && p() && e(1);             // 查看用户 1 负责的测试单中排除掉执行 1 的测试单并限制查询 1 个后的数量。
r($tasks) && p('2') && e('执行2 / 测试单2'); // 查看用户 1 负责的测试单中排除掉执行 1 的测试单并限制查询 1 个后的第 1 个测试单。

$tasks = $testtask->getUserTestTaskPairs('user1', 0, 'all', array(), array(2));
r(count($tasks)) && p() && e(1);             // 查看用户 1 负责的测试单中排除掉执行 2 的测试单后的数量。
r($tasks) && p('1') && e('执行1 / 测试单1'); // 查看用户 1 负责的测试单中排除掉执行 2 的测试单后的第 1 个测试单。
