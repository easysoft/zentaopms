#!/usr/bin/env php
<?php

/**

title=测试 branchZen::setParamsForLink();
timeout=0
cid=15343

- 执行invokeArgs($zen模块的newInstance方法，参数是, ['plan', '/test-%d-%d-%s', 1, 2]  @/test-1-2-{id}
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['program', '/test-%d-%d-%s', 1, 2]  @/test-1-2-{id}
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['project', '/test-%d-%s', 1, 2]  @/test-2-{id}
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['', '/test-%d-%d-%s', 1, 2]  @/test-1-2-{id}
- 执行invokeArgs($zen模块的newInstance方法，参数是, ['story', '/test-%d-%s', 1, 2]  @/test-2-{id}

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$zen  = initReference('branch');
$func = $zen->getMethod('setParamsForLink');

r($func->invokeArgs($zen->newInstance(), ['plan', '/test-%d-%d-%s', 1, 2])) && p() && e('/test-1-2-{id}');
r($func->invokeArgs($zen->newInstance(), ['program', '/test-%d-%d-%s', 1, 2])) && p() && e('/test-1-2-{id}');
r($func->invokeArgs($zen->newInstance(), ['project', '/test-%d-%s', 1, 2])) && p() && e('/test-2-{id}');
r($func->invokeArgs($zen->newInstance(), ['', '/test-%d-%d-%s', 1, 2])) && p() && e('/test-1-2-{id}');
r($func->invokeArgs($zen->newInstance(), ['story', '/test-%d-%s', 1, 2])) && p() && e('/test-2-{id}');