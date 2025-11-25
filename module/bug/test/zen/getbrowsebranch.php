#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 bugZen::getBrowseBranch();
timeout=0
cid=15450

- 执行invokeArgs($zen模块的newInstance方法，参数是, ['main', 'normal']  @all
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['branch1', 'branch']  @branch1
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['', 'branch']  @branch1
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['special', 'branch']  @special
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['123', 'branch']  @123

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'browse';

$zen = initReference('bug');
$func = $zen->getMethod('getBrowseBranch');

r($func->invokeArgs($zen->newInstance(), ['main', 'normal'])) && p() && e('all');
r($func->invokeArgs($zen->newInstance(), ['branch1', 'branch'])) && p() && e('branch1');
r($func->invokeArgs($zen->newInstance(), ['', 'branch'])) && p() && e('branch1');
r($func->invokeArgs($zen->newInstance(), ['special', 'branch'])) && p() && e('special');
r($func->invokeArgs($zen->newInstance(), ['123', 'branch'])) && p() && e('123');