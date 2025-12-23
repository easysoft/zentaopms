#!/usr/bin/env php
<?php

/**

title=测试 todoTao::updateDate();
timeout=0
cid=19282

- 执行todoTest模块的updateDateTest方法，参数是$singleTodoList, $validDate1  @1
- 执行todoTest模块的updateDateTest方法，参数是$multipleTodoList, $validDate2  @1
- 执行todoTest模块的updateDateTest方法，参数是$emptyTodoList, $validDateTime  @1
- 执行todoTest模块的updateDateTest方法，参数是$nonExistentList, $validDate1  @1
- 执行todoTest模块的updateDateTest方法，参数是$validTodoList, $edgeDate  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

zenData('todo')->loadYaml('updatedate')->gen(10);

su('admin');

$todoTest = new todoTest();

$validDate1    = '2024-01-15';
$validDate2    = '2024-02-20';
$validDateTime = '2024-03-25 14:30:00';
$edgeDate      = '2024-12-31';
$shortDate     = '2024-01-01';

$singleTodoList   = array(1);
$multipleTodoList = array(2, 3, 4);
$emptyTodoList    = array();
$nonExistentList  = array(999, 998);
$validTodoList    = array(5, 6);

r($todoTest->updateDateTest($singleTodoList, $validDate1)) && p() && e('1');
r($todoTest->updateDateTest($multipleTodoList, $validDate2)) && p() && e('1');
r($todoTest->updateDateTest($emptyTodoList, $validDateTime)) && p() && e('1');
r($todoTest->updateDateTest($nonExistentList, $validDate1)) && p() && e('1');
r($todoTest->updateDateTest($validTodoList, $edgeDate)) && p() && e('1');
