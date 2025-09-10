#!/usr/bin/env php
<?php

/**

title=测试 todoModel::dateRange();
timeout=0
cid=0

- 执行todoTest模块的dateRangeTest方法，参数是'future' 
 - 属性begin @2030-01-01
 - 属性end @2030-01-01
- 执行todoTest模块的dateRangeTest方法，参数是'20250101' 
 - 属性begin @2025-01-01 00:00:00
 - 属性end @2025-01-01 23:59:59
- 执行todoTest模块的dateRangeTest方法，参数是'today' 属性begin @2025-09-10
- 执行todoTest模块的dateRangeTest方法，参数是'before' 
 - 属性begin @
 - 属性end @2025-09-09
- 执行todoTest模块的dateRangeTest方法，参数是'yesterday' 属性begin @2025-09-09
- 执行todoTest模块的dateRangeTest方法，参数是'thisweek' 属性begin @2025-09-08 00:00:00
- 执行todoTest模块的dateRangeTest方法，参数是'invalid_type' 
 - 属性begin @
 - 属性end @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

su('admin');

global $tester;
$tester->loadModel('todo');
$tester->app->loadClass('date', true);

$todoTest = new todoTest();

r($todoTest->dateRangeTest('future')) && p('begin,end') && e('2030-01-01,2030-01-01');
r($todoTest->dateRangeTest('20250101')) && p('begin,end') && e('2025-01-01 00:00:00,2025-01-01 23:59:59');
r($todoTest->dateRangeTest('today')) && p('begin') && e('2025-09-10');
r($todoTest->dateRangeTest('before')) && p('begin,end') && e(',2025-09-09');
r($todoTest->dateRangeTest('yesterday')) && p('begin') && e('2025-09-09');
r($todoTest->dateRangeTest('thisweek')) && p('begin') && e('2025-09-08 00:00:00');
r($todoTest->dateRangeTest('invalid_type')) && p('begin,end') && e(',');