#!/usr/bin/env php
<?php
/**
title=测试 userTao->fetchExecutionTaskCount();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('task');
$table->execution->range('1-5{10}');
$table->parent->range('1,0{6}');
$table->assignedTo->range('user1{2},user2{4},user3');
$table->deleted->range('0{7},1');
$table->gen(50);

global $config;

$userTest = new userTest();

$executionIdList = array(1, 2, 3, 4, 5);

r($userTest->fetchExecutionTaskCountTest('user1', array()))          && p() && e(0); // 传入空数组，返回空数组。
r($userTest->fetchExecutionTaskCountTest('admin', $executionIdList)) && p() && e(0); // 用户 admin 没有参与执行，返回空数组。

$tasks = $userTest->fetchExecutionTaskCountTest('user1', $executionIdList);
r(count($tasks)) && p()  && e(4); // 用户 user1 参与了 4 个执行。
r($tasks)        && p(1) && e(2); // 执行 1 中指派给 user1 的任务数为 2。
r($tasks)        && p(3) && e(2); // 执行 3 中指派给 user1 的任务数为 2。
r($tasks)        && p(4) && e(1); // 执行 4 中指派给 user1 的任务数为 1。
r($tasks)        && p(5) && e(1); // 执行 5 中指派给 user1 的任务数为 1。

$tasks = $userTest->fetchExecutionTaskCountTest('user2', $executionIdList);
r(count($tasks)) && p()  && e(5); // 用户 user2 参与了 5 个执行。
r($tasks)        && p(1) && e(5); // 执行 1 中指派给 user2 的任务数为 5。
r($tasks)        && p(2) && e(7); // 执行 2 中指派给 user2 的任务数为 7。
r($tasks)        && p(3) && e(3); // 执行 3 中指派给 user2 的任务数为 3。
r($tasks)        && p(4) && e(5); // 执行 4 中指派给 user2 的任务数为 5。
r($tasks)        && p(5) && e(4); // 执行 5 中指派给 user2 的任务数为 4。

$tasks = $userTest->fetchExecutionTaskCountTest('user3', $executionIdList);
r(count($tasks)) && p()  && e(5); // 用户 user3 参与了 5 个执行。
r($tasks)        && p(1) && e(1); // 执行 1 中指派给 user3 的任务数为 1。
r($tasks)        && p(2) && e(1); // 执行 2 中指派给 user3 的任务数为 1。
r($tasks)        && p(3) && e(2); // 执行 3 中指派给 user3 的任务数为 2。
r($tasks)        && p(4) && e(1); // 执行 4 中指派给 user3 的任务数为 1。
r($tasks)        && p(5) && e(2); // 执行 5 中指派给 user3 的任务数为 2。
