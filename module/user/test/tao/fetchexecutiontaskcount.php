#!/usr/bin/env php
<?php
/**
title=测试 userTao->fetchExecutionTaskCount();
cid=19668

- 传入空数组，返回空数组。 @0
- 用户 admin 没有参与执行，返回空数组。 @0
- 用户 user1 参与了 5 个执行。 @5
- 执行 1 中指派给 user1 的任务数为 3。属性1 @3
- 执行 3 中指派给 user1 的任务数为 4。属性3 @4
- 执行 4 中指派给 user1 的任务数为 2。属性4 @2
- 执行 5 中指派给 user1 的任务数为 3。属性5 @3
- 用户 user2 参与了 5 个执行。 @5
- 执行 1 中指派给 user2 的任务数为 5。属性1 @5
- 执行 2 中指派给 user2 的任务数为 7。属性2 @7
- 执行 3 中指派给 user2 的任务数为 3。属性3 @3
- 执行 4 中指派给 user2 的任务数为 5。属性4 @5
- 执行 5 中指派给 user2 的任务数为 4。属性5 @4
- 用户 user3 参与了 5 个执行。 @5
- 执行 1 中指派给 user3 的任务数为 1。属性1 @1
- 执行 2 中指派给 user3 的任务数为 1。属性2 @1
- 执行 3 中指派给 user3 的任务数为 2。属性3 @2
- 执行 4 中指派给 user3 的任务数为 1。属性4 @1
- 执行 5 中指派给 user3 的任务数为 2。属性5 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

$table = zenData('task');
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
r(count($tasks)) && p()  && e(5); // 用户 user1 参与了 5 个执行。
r($tasks)        && p(1) && e(3); // 执行 1 中指派给 user1 的任务数为 3。
r($tasks)        && p(3) && e(4); // 执行 3 中指派给 user1 的任务数为 4。
r($tasks)        && p(4) && e(2); // 执行 4 中指派给 user1 的任务数为 2。
r($tasks)        && p(5) && e(3); // 执行 5 中指派给 user1 的任务数为 3。

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
