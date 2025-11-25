#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkTodoPriv();
timeout=0
cid=18324

- 执行searchTest模块的checkTodoPrivTest方法，参数是$results1, $objectIdList1, TABLE_TODO  @1
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results2, $objectIdList2, TABLE_TODO  @0
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results3, $objectIdList3, TABLE_TODO  @1
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results4, $objectIdList4, TABLE_TODO  @2
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results5, $objectIdList5, TABLE_TODO  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$todo = zenData('todo');
$todo->id->range('1-10');
$todo->account->range('admin,user1,user2,admin,user1,user2,admin,user1,user2,admin');
$todo->name->range('Todo 1,Todo 2,Todo 3,Todo 4,Todo 5,Todo 6,Todo 7,Todo 8,Todo 9,Todo 10');
$todo->private->range('0,1,1,0,1,0,1,0,1,1');
$todo->status->range('wait{5},doing{3},done{2}');
$todo->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectType' => 'todo', 'objectID' => 1));
$objectIdList1 = array(1 => 1);

$results2 = array(1 => (object)array('id' => 1, 'objectType' => 'todo', 'objectID' => 2));
$objectIdList2 = array(2 => 1);

$results3 = array(1 => (object)array('id' => 1, 'objectType' => 'todo', 'objectID' => 7));
$objectIdList3 = array(7 => 1);

$results4 = array(1 => (object)array('id' => 1, 'objectType' => 'todo', 'objectID' => 1), 2 => (object)array('id' => 2, 'objectType' => 'todo', 'objectID' => 4));
$objectIdList4 = array(1 => 1, 4 => 2);

$results5 = array(1 => (object)array('id' => 1, 'objectType' => 'todo', 'objectID' => 1), 2 => (object)array('id' => 2, 'objectType' => 'todo', 'objectID' => 4), 3 => (object)array('id' => 3, 'objectType' => 'todo', 'objectID' => 7));
$objectIdList5 = array(1 => 1, 4 => 2, 7 => 3);

r(count($searchTest->checkTodoPrivTest($results1, $objectIdList1, TABLE_TODO))) && p() && e('1');
r(count($searchTest->checkTodoPrivTest($results2, $objectIdList2, TABLE_TODO))) && p() && e('0');
r(count($searchTest->checkTodoPrivTest($results3, $objectIdList3, TABLE_TODO))) && p() && e('1');
r(count($searchTest->checkTodoPrivTest($results4, $objectIdList4, TABLE_TODO))) && p() && e('2');
r(count($searchTest->checkTodoPrivTest($results5, $objectIdList5, TABLE_TODO))) && p() && e('3');