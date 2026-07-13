#!/usr/bin/env php
<?php

/**

title=测试 ppmZen::processLinkTaskPager();
timeout=0
cid=0

- 执行processLinkTaskPagerTest(5, 2, 1, $tasks)模块的allTasks方法  @2
- 执行ppmZen模块的processLinkTaskPagerTest方法，参数是5, 2, 1, $tasks 第pager条的pageTotal属性 @3
- 执行processLinkTaskPagerTest(5, 2, 2, $tasks)模块的allTasks方法  @2
- 执行processLinkTaskPagerTest(5, 2, 3, $tasks)模块的allTasks方法  @1
- 执行ppmZen模块的processLinkTaskPagerTest方法，参数是5, 2, 5, $tasks 第pager条的pageID属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->rawModule = 'ppm';
$app->rawMethod = 'view';
$app->setMethodName('view');

su('admin');

$ppmZen = new ppmZenTest();
$tasks  = array(
    1 => (object)array('id' => 1, 'name' => 'Task 1'),
    2 => (object)array('id' => 2, 'name' => 'Task 2'),
    3 => (object)array('id' => 3, 'name' => 'Task 3'),
    4 => (object)array('id' => 4, 'name' => 'Task 4'),
    5 => (object)array('id' => 5, 'name' => 'Task 5'),
);

r(count($ppmZen->processLinkTaskPagerTest(5, 2, 1, $tasks)->allTasks)) && p() && e('2');
r($ppmZen->processLinkTaskPagerTest(5, 2, 1, $tasks)) && p('pager:pageTotal') && e('3');
r(count($ppmZen->processLinkTaskPagerTest(5, 2, 2, $tasks)->allTasks)) && p() && e('2');
r(count($ppmZen->processLinkTaskPagerTest(5, 2, 3, $tasks)->allTasks)) && p() && e('1');
r($ppmZen->processLinkTaskPagerTest(5, 2, 5, $tasks)) && p('pager:pageID') && e('1');