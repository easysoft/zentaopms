#!/usr/bin/env php
<?php

/**

title=测试 mrZen::processLinkTaskPager();
timeout=0
cid=0

- 执行mrTest模块的processLinkTaskPagerTest方法，参数是50, 5, 1, array 属性filtered_task_count @5
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是50, 5, 10, array 属性page_id @1
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是50, 10, 1, array 属性filtered_task_count @0
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是50, 1, 2, array 属性filtered_task_count @1
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是50, 3, 2, array 属性filtered_task_count @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

su('admin');

$mrTest = new mrTest();

r($mrTest->processLinkTaskPagerTest(50, 5, 1, array('task1' => (object)array('id' => 1, 'name' => 'Task 1'), 'task2' => (object)array('id' => 2, 'name' => 'Task 2'), 'task3' => (object)array('id' => 3, 'name' => 'Task 3'), 'task4' => (object)array('id' => 4, 'name' => 'Task 4'), 'task5' => (object)array('id' => 5, 'name' => 'Task 5'), 'task6' => (object)array('id' => 6, 'name' => 'Task 6'), 'task7' => (object)array('id' => 7, 'name' => 'Task 7'), 'task8' => (object)array('id' => 8, 'name' => 'Task 8'), 'task9' => (object)array('id' => 9, 'name' => 'Task 9'), 'task10' => (object)array('id' => 10, 'name' => 'Task 10')))) && p('filtered_task_count') && e('5');
r($mrTest->processLinkTaskPagerTest(50, 5, 10, array('task1' => (object)array('id' => 1, 'name' => 'Task 1'), 'task2' => (object)array('id' => 2, 'name' => 'Task 2'), 'task3' => (object)array('id' => 3, 'name' => 'Task 3')))) && p('page_id') && e('1');
r($mrTest->processLinkTaskPagerTest(50, 10, 1, array())) && p('filtered_task_count') && e('0');
r($mrTest->processLinkTaskPagerTest(50, 1, 2, array('task1' => (object)array('id' => 1, 'name' => 'Task 1'), 'task2' => (object)array('id' => 2, 'name' => 'Task 2'), 'task3' => (object)array('id' => 3, 'name' => 'Task 3')))) && p('filtered_task_count') && e('1');
r($mrTest->processLinkTaskPagerTest(50, 3, 2, array('task1' => (object)array('id' => 1, 'name' => 'Task 1'), 'task2' => (object)array('id' => 2, 'name' => 'Task 2'), 'task3' => (object)array('id' => 3, 'name' => 'Task 3'), 'task4' => (object)array('id' => 4, 'name' => 'Task 4'), 'task5' => (object)array('id' => 5, 'name' => 'Task 5'), 'task6' => (object)array('id' => 6, 'name' => 'Task 6'), 'task7' => (object)array('id' => 7, 'name' => 'Task 7'), 'task8' => (object)array('id' => 8, 'name' => 'Task 8')))) && p('filtered_task_count') && e('3');