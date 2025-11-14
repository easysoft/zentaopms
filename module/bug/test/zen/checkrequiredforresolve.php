#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$table = zenData('project');
$table->id->range('11-20');
$table->name->range('execution{1-10}');
$table->type->range('sprint{5},kanban{5}');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

/**

title=测试 bugZen::checkRequiredForResolve();
timeout=0
cid=15444

- 执行invokeArgs($zen模块的newInstance方法，参数是, [$bug1, 0]  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$bug2, 11]  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$bug3, 0]  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$bug4, 0]  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$bug5, 0]  @0

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'resolve';

$zen = initReference('bug');
$func = $zen->getMethod('checkRequiredForResolve');

$bug1 = (object)array('resolution' => 'fixed', 'resolvedBuild' => '1', 'createBuild' => '', 'duplicateBug' => '');
$bug2 = (object)array('createBuild' => 'on', 'buildExecution' => '', 'buildName' => 'test-build', 'resolution' => '', 'duplicateBug' => '', 'resolvedBuild' => '');
$bug3 = (object)array('createBuild' => 'on', 'buildExecution' => '11', 'buildName' => '', 'resolution' => '', 'duplicateBug' => '', 'resolvedBuild' => '');
$bug4 = (object)array('resolution' => 'duplicate', 'duplicateBug' => '', 'createBuild' => '', 'resolvedBuild' => '');
$bug5 = (object)array('resolution' => 'fixed', 'resolvedBuild' => '', 'createBuild' => '', 'duplicateBug' => '');

r($func->invokeArgs($zen->newInstance(), [$bug1, 0])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), [$bug2, 11])) && p() && e('0');
r($func->invokeArgs($zen->newInstance(), [$bug3, 0])) && p() && e('0');
r($func->invokeArgs($zen->newInstance(), [$bug4, 0])) && p() && e('0');
r($func->invokeArgs($zen->newInstance(), [$bug5, 0])) && p() && e('0');