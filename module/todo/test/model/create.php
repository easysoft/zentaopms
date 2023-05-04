#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';

su('admin');

/**

title=测试 todoModel->create();
timeout=0
cid=1

- 执行todoTest模块的createTest方法，参数是$todoWithoutName, $formData @0

- 执行todoTest模块的createTest方法，参数是$todoInvalidEnd, $formData @0

- 执行todoTest模块的createTest方法，参数是$todo, $formData @2

- 执行todoTest模块的createTest方法，参数是$todoWithCycle, $formData @3

*/

global $tester;
$tester->loadModel('todo');

zdTable('todo')->config('create')->gen(5);
$today = date('Y-m-d');

$formData = new stdClass;
$formData->rawdata = new stdclass;
$formData->rawdata->uid = '';

$todo = new stdclass;
$todo->name         = 'TODO Create Test';
$todo->account      = 'admin';
$todo->date         = $today;
$todo->type         = 'custom';
$todo->begin        = '0800';
$todo->end          = '1700';
$todo->assignedTo   = 'admin';
$todo->assignedBy   = 'admin';
$todo->assignedDate = $today;

$todoWithoutName = clone $todo;
$todoWithoutName->name = '';

$todoInvalidEnd = clone $todo;
$todoInvalidEnd->name  = 'todoInvalidDate';
$todoInvalidEnd->begin = '1000';
$todoInvalidEnd->end   = '0800';

$todoWithCycle = clone $todo;
$todoWithCycle->type     = 'cycle';
$todoWithCycle->cycle    = 1;
$todoWithCycle->config   = array('day' => 1, 'specify' => array('month' => 0, 'day' => 1), 'type' => 'day', 'beforeDays' => 1, 'end' => '');
$todoWithCycle->objectID = 0;

$todoTest = new todoTest();
r($todoTest->createTest($todoWithoutName, $formData))    && p() && e('0');
r($todoTest->createTest($todoInvalidEnd, $formData))     && p() && e('0');
r($todoTest->createTest($todo, $formData))               && p() && e('6');
r($todoTest->createTest($todoWithCycle, $formData))      && p() && e('7');
