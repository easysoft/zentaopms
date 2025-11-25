#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$table = zenData('bug');
$table->id->range('1-5');
$table->title->range('测试Bug1,测试Bug2,测试Bug3,测试Bug4,测试Bug5');
$table->execution->range('0,0,0,0,0');
$table->product->range('1');
$table->status->range('active');
$table->openedBy->range('admin');
$table->gen(5);

su('admin');

/**

title=测试 bugZen::checkBugExecutionPriv();
timeout=0
cid=15440

- 执行invokeArgs($zen模块的newInstance方法，参数是, [  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [  @1

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'view';

$zen = initReference('bug');
$func = $zen->getMethod('checkBugExecutionPriv');

r($func->invokeArgs($zen->newInstance(), [(object)array('id' => 1, 'execution' => 0)])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), [(object)array('id' => 2, 'execution' => '')])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), [(object)array('id' => 3, 'execution' => null)])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), [(object)array('id' => 4, 'execution' => 0)])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), [(object)array('id' => 5, 'execution' => false)])) && p() && e('1');