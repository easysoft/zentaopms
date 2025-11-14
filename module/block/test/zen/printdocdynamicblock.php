#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocDynamicBlock();
timeout=0
cid=15254

- 测试1:返回对象包含actionsCount属性actionsCount @0
- 测试2:返回对象包含usersCount属性usersCount @11
- 测试3:多次调用actionsCount一致属性actionsCount @0
- 测试4:多次调用usersCount一致属性usersCount @11
- 测试5:验证数据结构完整
 - 属性actionsCount @0
 - 属性usersCount @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(10);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printDocDynamicBlockTest()) && p('actionsCount') && e('0'); // 测试1:返回对象包含actionsCount
r($blockTest->printDocDynamicBlockTest()) && p('usersCount') && e('11'); // 测试2:返回对象包含usersCount
r($blockTest->printDocDynamicBlockTest()) && p('actionsCount') && e('0'); // 测试3:多次调用actionsCount一致
r($blockTest->printDocDynamicBlockTest()) && p('usersCount') && e('11'); // 测试4:多次调用usersCount一致
r($blockTest->printDocDynamicBlockTest()) && p('actionsCount,usersCount') && e('0,11'); // 测试5:验证数据结构完整