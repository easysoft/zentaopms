#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . 'test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';

su('admin');

/**

title=测试 todoModel->create();
timeout=0
cid=1

- 执行todoTest模块的createTest方法，参数是$todoWithoutName @0

- 执行todoTest模块的createTest方法，参数是$todoInvalidObjectID @0

- 执行todoTest模块的createTest方法，参数是$todo @6

- 执行todoTest模块的createTest方法，参数是$todoWithCycle @7

*/

global $tester;
$tester->loadModel('todo');

zdTable('todo')->config('create')->gen(5);
$today = date('Y-m-d');

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

$todoInvalidObjectID = clone $todo;
$randModuleKey       = array_rand($tester->config->todo->moduleList, 1);
$todoInvalidObjectID->name     = 'todoInvalidObjectID';
$todoInvalidObjectID->type     = $tester->config->todo->moduleList[$randModuleKey];
$todoInvalidObjectID->objectID = 0;

$todoWithCycle = clone $todo;
$todoWithCycle->type     = 'cycle';
$todoWithCycle->cycle    = 1;
$todoWithCycle->config   = json_encode(array('day' => 1, 'specify' => array('month' => 0, 'day' => 1), 'type' => 'day', 'beforeDays' => 1, 'end' => ''));
$todoWithCycle->objectID = 0;

$todoTest = new todoTest();
r($todoTest->createTest($todoWithoutName))     && p() && e('0');
r($todoTest->createTest($todoInvalidObjectID)) && p() && e('0');
r($todoTest->createTest($todo))                && p() && e('6');
r($todoTest->createTest($todoWithCycle))       && p() && e('7');
