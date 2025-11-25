#!/usr/bin/env php
<?php

/**

title=测试 todoModel::dateRange();
timeout=0
cid=19253

- 执行todo模块的dateRangeTest方法，参数是'future'
 - 属性begin @2030-01-01
 - 属性end @2030-01-01
- 执行todo模块的dateRangeTest方法，参数是'20210101'
 - 属性begin @2021-01-01 00:00:00
 - 属性end @2021-01-01 23:59:59
- 执行todo模块的dateRangeTest方法，参数是'before' 属性end @2025-11-05
- 执行todo模块的dateRangeTest方法，参数是'today' 属性begin @2025-11-06
- 执行todo模块的dateRangeTest方法，参数是'yesterday' 属性begin @2025-11-05
- 执行todo模块的dateRangeTest方法，参数是'thisweek' 属性begin @2025-11-03 00:00:00
- 执行todo模块的dateRangeTest方法，参数是'lastweek' 属性begin @2025-10-27 00:00:00
- 执行todo模块的dateRangeTest方法，参数是'thismonth' 属性begin @2025-11-01 00:00:00
- 执行todo模块的dateRangeTest方法，参数是'lastmonth' 属性begin @2025-10-01 00:00:00
- 执行todo模块的dateRangeTest方法，参数是'thisseason' 属性begin @2025-10-01 00:00:00

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

global $tester;
$tester->app->loadClass('date', true);

$todo = new todoTest();

r($todo->dateRangeTest('future')) && p('begin,end') && e('2030-01-01,2030-01-01');
r($todo->dateRangeTest('20210101')) && p('begin,end') && e('2021-01-01 00:00:00,2021-01-01 23:59:59');
r($todo->dateRangeTest('before')) && p('end') && e('2025-11-05');
r($todo->dateRangeTest('today')) && p('begin') && e('2025-11-06');
r($todo->dateRangeTest('yesterday')) && p('begin') && e('2025-11-05');
r($todo->dateRangeTest('thisweek')) && p('begin') && e('2025-11-03 00:00:00');
r($todo->dateRangeTest('lastweek')) && p('begin') && e('2025-10-27 00:00:00');
r($todo->dateRangeTest('thismonth')) && p('begin') && e('2025-11-01 00:00:00');
r($todo->dateRangeTest('lastmonth')) && p('begin') && e('2025-10-01 00:00:00');
r($todo->dateRangeTest('thisseason')) && p('begin') && e('2025-10-01 00:00:00');