#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

/**

title=测试 todoTao->getValidsOfBatchCreate();
cid=1
pid=1

*/

$today       = date('Y-m-d');
$assignedTo  = 'admin';
$names       = array('批量创建待办1', '批量创建待办2', '批量创建待办3', '批量创建待办4', '批量创建待办5', '批量创建待办6', '批量创建待办7', '批量创建待办8');
$types       = array('custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom');
$pris        = array(1, 2, 3, 4, 1, 2, 3, 4);
$descs       = array('desc1', 'desc2', 'desc3', 'desc4', '' , '', '', '');
$begins      = array('0830', '0900', '0930', '1000', '1030', '1100', '1130', '1200');
$ends        = array('0900', '0930', '1000','1030', '1100', '1130', '1200', '1230');
$assignedTos = array('admin', 'productManager', 'projectManager', 'dev1', 'dev2', 'dev3', 'tester1', 'tester2',);
$todos       = array('types' => $types, 'pris' => $pris, 'names' => $names, 'descs' => $descs, 'begins' => $begins, 'ends' => $ends, 'assignedTos' => $assignedTos, 'date' => $today, 'switchDate' => '');

$invalidEnds     = array('0800', '0830', '0800', '0800', '0800', '0800', '0800', '0800');
$invalidEndTodos = $todos;
$invalidEndTodos['ends'] = $invalidEnds;

$todoTest   = new todoTest();
r($todoTest->getValidsOfBatchCreateTest($todos, $loop = 1, $assignedTo))           && p() && e('1'); // 获取批量创建有效的数据，结果为1
r($todoTest->getValidsOfBatchCreateTest($invalidEndTodos, $loop = 1, $assignedTo)) && p() && e('0'); // 获取批量创建有效的数据，字段ends数据错误时，结果为1

