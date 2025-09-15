#!/usr/bin/env php
<?php

/**

title=测试 bugZen::checkRquiredForEdit();
timeout=0
cid=0

- 无错误，验证通过 @0
- 执行$errors属性title @『Bug标题』不能为空。
- 执行$errors属性openedBuild[] @『影响版本』不能为空。
- 执行$errors属性resolution @『解决方案』不能为空。
- 执行$errors属性duplicateBug @『重复Bug』不能为空。
- 执行$errors
 - 属性title @『Bug标题』不能为空。
 - 属性openedBuild[] @『影响版本』不能为空。
- 执行$errors属性title @『Bug标题』不能为空。
- 执行$errors属性duplicateBug @『重复Bug』不能为空。
- 执行$errors
 - 属性title @『Bug标题』不能为空。
 - 属性openedBuild[] @『影响版本』不能为空。
- 执行$errors @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// zendata数据准备
$table = zenData('bug');
$table->id->range('1-10');
$table->title->range('测试Bug1,测试Bug2,测试Bug3,测试Bug4,测试Bug5,测试Bug6,测试Bug7,测试Bug8,测试Bug9,测试Bug10');
$table->product->range('1');
$table->status->range('active');
$table->openedBy->range('admin');
$table->gen(10);

// 设置必填字段配置
global $tester, $app, $config;
$app->rawModule = 'bug';
$app->rawMethod = 'edit';
$config->bug->edit->requiredFields = 'title,openedBuild';

su('admin');

// 使用initReference获取zen对象
$zen = initReference('bug');
$func = $zen->getMethod('checkRquiredForEdit');

// 步骤1：测试正常情况，所有必填字段都填写
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(count(dao::$errors)) && p() && e(0); // 无错误，验证通过

// 步骤2：测试title字段为空
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => '', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(dao::$errors) && p('title') && e('『Bug标题』不能为空。');

// 步骤3：测试openedBuild字段为空
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => '', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(dao::$errors) && p('openedBuild[]') && e('『影响版本』不能为空。');

// 步骤4：测试resolvedBy有值但resolution为空
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => 'admin', 'resolution' => '', 'duplicateBug' => '')]);
r(dao::$errors) && p('resolution') && e('『解决方案』不能为空。');

// 步骤5：测试resolution为duplicate但duplicateBug为空
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'duplicate', 'duplicateBug' => '')]);
r(dao::$errors) && p('duplicateBug') && e('『重复Bug』不能为空。');

// 步骤6：测试多个必填字段都为空
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => '', 'openedBuild' => '', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(dao::$errors) && p('title,openedBuild[]') && e('『Bug标题』不能为空。,『影响版本』不能为空。');

// 步骤7：测试title为空字符串（只有空格）的边界情况
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => '   ', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => '')]);
r(dao::$errors) && p('title') && e('『Bug标题』不能为空。');

// 步骤8：测试resolution为duplicate且duplicateBug为0
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'duplicate', 'duplicateBug' => '0')]);
r(dao::$errors) && p('duplicateBug') && e('『重复Bug』不能为空。');

// 步骤9：测试所有字段为null的情况
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => null, 'openedBuild' => null, 'resolvedBy' => null, 'resolution' => null, 'duplicateBug' => null)]);
r(dao::$errors) && p('title,openedBuild[]') && e('『Bug标题』不能为空。,『影响版本』不能为空。');

// 步骤10：测试resolution为非duplicate时duplicateBug为空（不应产生duplicateBug错误）
dao::$errors = array();
$func->invokeArgs($zen->newInstance(), [(object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'fixed', 'duplicateBug' => '')]);
r(count(dao::$errors)) && p() && e(0);