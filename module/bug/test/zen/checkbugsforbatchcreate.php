#!/usr/bin/env php
<?php

/**

title=测试 bugZen::checkBugsForBatchCreate();
timeout=0
cid=15441

- 执行$result @1
- 执行$result @1
- 执行$errors属性openedBuild[0] @『影响版本』不能为空。
- 执行$result @0
- 执行$errors @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'batchcreate';

$zen = initReference('bug');
$func = $zen->getMethod('checkBugsForBatchCreate');

// 测试步骤1：检查有效的批量创建bug数据
dao::$errors = array();
$result = $func->invokeArgs($zen->newInstance(), [array((object)array('title' => 'Bug1', 'openedBuild' => 'trunk'))]);
r(count($result)) && p() && e('1');

// 测试步骤2：检查缺少title字段的bug数据（title非必填）
dao::$errors = array();
$result = $func->invokeArgs($zen->newInstance(), [array((object)array('openedBuild' => 'trunk'))]);
r(count($result)) && p() && e('1');

// 测试步骤3：检查缺少openedBuild字段的bug数据
dao::$errors = array();
$result = $func->invokeArgs($zen->newInstance(), [array((object)array('title' => 'Bug without build'))]);
r(dao::$errors) && p('openedBuild[0]') && e('『影响版本』不能为空。');

// 测试步骤4：检查空数组输入
dao::$errors = array();
$result = $func->invokeArgs($zen->newInstance(), [array()]);
r(count($result)) && p() && e('0');

// 测试步骤5：检查包含空对象的数组
dao::$errors = array();
$result = $func->invokeArgs($zen->newInstance(), [array((object)array(), (object)array())]);
r(count(dao::$errors)) && p() && e('2');