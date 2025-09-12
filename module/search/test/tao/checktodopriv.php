#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkTodoPriv();
timeout=0
cid=0

- 执行searchTest模块的checkTodoPrivTest方法，参数是$results1, $objectIdList1, TABLE_TODO  @1
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results2, $objectIdList2, TABLE_TODO  @2
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results3, $objectIdList3, TABLE_TODO  @1
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results4, $objectIdList4, TABLE_TODO  @0
- 执行searchTest模块的checkTodoPrivTest方法，参数是$results5, $objectIdList5, TABLE_TODO  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

$table = zenData('todo');
$table->id->range('1-5');
$table->account->range('admin,user1,user2,admin,user3');
$table->name->range('待办1,待办2,待办3,待办4,待办5');
$table->private->range('1,1,0,0,1');
$table->status->range('wait');
$table->gen(5);

su('admin');

$searchTest = new searchTest();

$results1 = array(
    101 => (object)array('id' => 101, 'objectID' => 1, 'objectType' => 'todo'),
    102 => (object)array('id' => 102, 'objectID' => 2, 'objectType' => 'todo')
);
$objectIdList1 = array(1 => 101, 2 => 102);

$results2 = array(
    101 => (object)array('id' => 101, 'objectID' => 1, 'objectType' => 'todo'),
    104 => (object)array('id' => 104, 'objectID' => 4, 'objectType' => 'todo')
);
$objectIdList2 = array(1 => 101, 4 => 104);

$results3 = array(
    103 => (object)array('id' => 103, 'objectID' => 3, 'objectType' => 'todo')
);
$objectIdList3 = array(3 => 103);

$results4 = array();
$objectIdList4 = array();

$results5 = array(
    199 => (object)array('id' => 199, 'objectID' => 999, 'objectType' => 'todo')
);
$objectIdList5 = array(999 => 199);

r($searchTest->checkTodoPrivTest($results1, $objectIdList1, TABLE_TODO)) && p() && e('1');
r($searchTest->checkTodoPrivTest($results2, $objectIdList2, TABLE_TODO)) && p() && e('2');
r($searchTest->checkTodoPrivTest($results3, $objectIdList3, TABLE_TODO)) && p() && e('1');
r($searchTest->checkTodoPrivTest($results4, $objectIdList4, TABLE_TODO)) && p() && e('0');
r($searchTest->checkTodoPrivTest($results5, $objectIdList5, TABLE_TODO)) && p() && e('1');