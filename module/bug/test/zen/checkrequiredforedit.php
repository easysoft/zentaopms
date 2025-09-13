#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$table = zenData('bug');
$table->id->range('1-5');
$table->title->range('测试Bug1,测试Bug2,测试Bug3,测试Bug4,测试Bug5');
$table->product->range('1');
$table->status->range('active');
$table->openedBy->range('admin');
$table->gen(5);

su('admin');

/**

title=测试 bugZen::checkRquiredForEdit();
timeout=0
cid=0

- 执行$errors @0
- 执行$errors属性title @『Bug标题』不能为空。
- 执行$errors属性resolution @『解决方案』不能为空。
- 执行$errors属性duplicateBug @『重复Bug』不能为空。
- 执行$errors @2

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'edit';

$zen = initReference('bug');
$func = $zen->getMethod('checkRquiredForEdit');

// 清空错误，测试正常情况
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(count(dao::$errors)) && p() && e(0);

// 清空错误，测试title为空的情况
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => '', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(dao::$errors) && p('title') && e('『Bug标题』不能为空。');

// 清空错误，测试解决方案为空但解决人已填写的情况
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => 'admin', 'resolution' => '', 'duplicateBug' => '')]);
r(dao::$errors) && p('resolution') && e('『解决方案』不能为空。');

// 清空错误，测试解决方案为duplicate但重复Bug为空的情况
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'duplicate', 'duplicateBug' => '')]);
r(dao::$errors) && p('duplicateBug') && e('『重复Bug』不能为空。');

// 清空错误，测试多个必填字段都为空的情况
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => '', 'openedBuild' => '', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(count(dao::$errors)) && p() && e(2);