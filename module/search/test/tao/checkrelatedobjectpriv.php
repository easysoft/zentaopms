#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkRelatedObjectPriv();
timeout=0
cid=18322

- 执行searchTest模块的checkRelatedObjectPrivTest方法，参数是'bug', TABLE_BUG, $results1, $objectIdList1, $products1, $executions1 第1条的objectID属性 @1
- 执行searchTest模块的checkRelatedObjectPrivTest方法，参数是'bug', TABLE_BUG, $results2, $objectIdList2, $products2, $executions2  @0
- 执行searchTest模块的checkRelatedObjectPrivTest方法，参数是'task', TABLE_TASK, $results3, $objectIdList3, $products3, $executions3 第1条的objectID属性 @1
- 执行searchTest模块的checkRelatedObjectPrivTest方法，参数是'task', TABLE_TASK, $results4, $objectIdList4, $products4, $executions4  @0
- 执行searchTest模块的checkRelatedObjectPrivTest方法，参数是'effort', TABLE_EFFORT, $results5, $objectIdList5, $products5, $executions5 第1条的objectID属性 @1
- 执行searchTest模块的checkRelatedObjectPrivTest方法，参数是'bug', TABLE_BUG, $results6, $objectIdList6, $products6, $executions6  @0
- 执行searchTest模块的checkRelatedObjectPrivTest方法，参数是'project', TABLE_PROJECT, $results7, $objectIdList7, $products7, $executions7 第1条的objectID属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->title->range('Bug 1,Bug 2,Bug 3,Bug 4,Bug 5,Bug 6,Bug 7,Bug 8,Bug 9,Bug 10');
$bug->product->range('1-10');
$bug->gen(10);

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('Task 1,Task 2,Task 3,Task 4,Task 5,Task 6,Task 7,Task 8,Task 9,Task 10');
$task->execution->range('1-10');
$task->gen(10);

$effort = zenData('effort');
$effort->id->range('1-5');
$effort->product->range(',1,,2,3,');
$effort->execution->range('1,2,3,4,5');
$effort->gen(5);

$story = zenData('story');
$story->id->range('1-10');
$story->title->range('Story 1,Story 2,Story 3,Story 4,Story 5,Story 6,Story 7,Story 8,Story 9,Story 10');
$story->product->range('1-10');
$story->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'bug', 'title' => 'Test Bug 1'));
$objectIdList1 = array(1 => 1);
$products1 = '1,2,3,4,5';
$executions1 = '1,2,3,4,5';
r($searchTest->checkRelatedObjectPrivTest('bug', TABLE_BUG, $results1, $objectIdList1, $products1, $executions1)) && p('1:objectID') && e('1');

$results2 = array(1 => (object)array('id' => 1, 'objectID' => 2, 'objectType' => 'bug', 'title' => 'Test Bug 2'));
$objectIdList2 = array(2 => 1);
$products2 = '1,3,4,5';
$executions2 = '1,2,3,4,5';
r(count($searchTest->checkRelatedObjectPrivTest('bug', TABLE_BUG, $results2, $objectIdList2, $products2, $executions2))) && p() && e('0');

$results3 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'task', 'title' => 'Test Task 1'));
$objectIdList3 = array(1 => 1);
$products3 = '1,2,3,4,5';
$executions3 = '1,2,3,4,5';
r($searchTest->checkRelatedObjectPrivTest('task', TABLE_TASK, $results3, $objectIdList3, $products3, $executions3)) && p('1:objectID') && e('1');

$results4 = array(1 => (object)array('id' => 1, 'objectID' => 3, 'objectType' => 'task', 'title' => 'Test Task 3'));
$objectIdList4 = array(3 => 1);
$products4 = '1,2,3,4,5';
$executions4 = '1,2,4,5';
r(count($searchTest->checkRelatedObjectPrivTest('task', TABLE_TASK, $results4, $objectIdList4, $products4, $executions4))) && p() && e('0');

$results5 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'effort', 'title' => 'Test Effort 1'));
$objectIdList5 = array(1 => 1);
$products5 = '1,2,3,4,5';
$executions5 = '1,2,3,4,5';
r($searchTest->checkRelatedObjectPrivTest('effort', TABLE_EFFORT, $results5, $objectIdList5, $products5, $executions5)) && p('1:objectID') && e('1');

$results6 = array();
$objectIdList6 = array();
$products6 = '1,2,3,4,5';
$executions6 = '1,2,3,4,5';
r(count($searchTest->checkRelatedObjectPrivTest('bug', TABLE_BUG, $results6, $objectIdList6, $products6, $executions6))) && p() && e('0');

$results7 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'project', 'title' => 'Test Project 1'));
$objectIdList7 = array(1 => 1);
$products7 = '1,2,3,4,5';
$executions7 = '1,2,3,4,5';
r($searchTest->checkRelatedObjectPrivTest('project', TABLE_PROJECT, $results7, $objectIdList7, $products7, $executions7)) && p('1:objectID') && e('1');