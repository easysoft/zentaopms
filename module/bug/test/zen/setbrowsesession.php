#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 bugZen::setBrowseSession();
timeout=0
cid=15477

- 执行invokeArgs($zen模块的newInstance方法，参数是, ['all']  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['bymodule']  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['bysearch']  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['']  @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['unclosed']  @1

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'browse';

$zen = initReference('bug');
$func = $zen->getMethod('setBrowseSession');

r($func->invokeArgs($zen->newInstance(), ['all'])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), ['bymodule'])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), ['bysearch'])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), [''])) && p() && e('1');
r($func->invokeArgs($zen->newInstance(), ['unclosed'])) && p() && e('1');