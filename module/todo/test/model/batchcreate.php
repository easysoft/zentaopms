#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    zdTable('todo')->config('batchcreate')->gen(5);
}

/**

title=测试 todoModel->batchCreate();
cid=1
pid=1

- 执行todo模块的batchCreateTest方法，参数是$todos, $formData属性7 @13
- 执行todo模块的batchCreateTest方法，参数是$invalidEndTodos, $formData @0

*/

initData();

$today    = date('Y-m-d');
$formData = new stdClass;
$formData->rawdata = new stdClass;
$formData->rawdata->uid        = '';
$formData->rawdata->date       = $today;
$formData->rawdata->switchDate = '';

$names       = array('批量创建待办1', '批量创建待办2', '批量创建待办3', '批量创建待办4', '批量创建待办5', '批量创建待办6', '批量创建待办7', '批量创建待办8');
$types       = array('custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom');
$pris        = array(1, 2, 3, 4, 1, 2, 3, 4);
$descs       = array('desc1', 'desc2', 'desc3', 'desc4', '' , '', '', '');
$begins      = array('0830', '0900', '0930', '1000', '1030', '1100', '1130', '1200');
$ends        = array('0900', '0930', '1000','1030', '1100', '1130', '1200', '1230');
$assignedTos = array('admin', 'productManager', 'projectManager', 'dev1', 'dev2', 'dev3', 'tester1', 'tester2',);
$todos       = array('types' => $types, 'pris' => $pris, 'names' => $names, 'descs' => $descs, 'begins' => $begins, 'ends' => $ends, 'assignedTos' => $assignedTos, 'date' => $today);

$invalidEnds     = array('0800', '0830', '0800','0800', '0800', '0800', '0800', '0800');
$invalidEndTodos = $todos;
$invalidEndTodos['ends'] = $invalidEnds;

$todo = new todoTest();
r($todo->batchCreateTest($todos, $formData)) && p('7') && e('13');
r($todo->batchCreateTest($invalidEndTodos, $formData)) && p() && e('0');

