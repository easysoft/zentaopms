#!/usr/bin/env php
<?php

/**

title=测试 todoZen::handleCycleConfig();
timeout=0
cid=19303

- 执行todoTest模块的handleCycleConfigTest方法，参数是'day' 属性type @day
- 执行todoTest模块的handleCycleConfigTest方法，参数是'week' 属性type @week
- 执行todoTest模块的handleCycleConfigTest方法，参数是'month' 属性type @month
- 执行todoTest模块的handleCycleConfigTest方法，参数是'day_empty_beforedays' 属性beforeDays @0
- 执行todoTest模块的handleCycleConfigTest方法，参数是'day_with_beforedays' 属性beforeDays @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zenData('todo')->gen(0);

su('admin');

$todoTest = new todoTest();

r($todoTest->handleCycleConfigTest('day')) && p('type') && e('day');
r($todoTest->handleCycleConfigTest('week')) && p('type') && e('week');
r($todoTest->handleCycleConfigTest('month')) && p('type') && e('month');
r($todoTest->handleCycleConfigTest('day_empty_beforedays')) && p('beforeDays') && e('0');
r($todoTest->handleCycleConfigTest('day_with_beforedays')) && p('beforeDays') && e('3');