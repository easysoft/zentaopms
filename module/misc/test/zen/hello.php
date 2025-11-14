#!/usr/bin/env php
<?php

/**

title=测试 miscZen::hello();
timeout=0
cid=17219

- 执行 @hello world from hello()<br />
- 执行 @hello world from hello()<br />
- 执行 @1
- 执行 @1
- 执行 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$zen = initReference('misc');
$func = $zen->getMethod('hello');

function testMiscHello() {
    global $zen, $func;
    $instance = $zen->newInstance();
    return $func->invokeArgs($instance, []);
}

r(testMiscHello()) && p() && e('hello world from hello()<br />');
r(testMiscHello()) && p() && e('hello world from hello()<br />');
r(is_string(testMiscHello())) && p() && e('1');
r(strpos(testMiscHello(), 'hello world') !== false) && p() && e('1');
r(strpos(testMiscHello(), '<br />') !== false) && p() && e('1');