#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

zdTable('todo')->config('create')->gen(5);

/**

title=测试 todoModel->create();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('todo');
$today = date('Y-m-d');

$todo = new stdclass();
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

$randModuleKey = array_rand($tester->config->todo->moduleList, 1);

$todoInvalidObjectID = clone $todo;
$todoInvalidObjectID->name     = 'todoInvalidObjectID';
$todoInvalidObjectID->type     = $tester->config->todo->moduleList[$randModuleKey];
$todoInvalidObjectID->objectID = 0;

$todoWithCycle = clone $todo;
$todoWithCycle->type     = 'cycle';
$todoWithCycle->cycle    = 1;
$todoWithCycle->config   = json_encode(array('day' => 1, 'specify' => array('month' => 0, 'day' => 1), 'type' => 'day', 'beforeDays' => 1, 'end' => ''));
$todoWithCycle->objectID = 0;

$todoTest = new todoTest();
r($todoTest->createTest($todoWithoutName))     && p() && e('0'); // 判断创建的待办数据name字段为空，返回结果id为0
r($todoTest->createTest($todoInvalidObjectID)) && p() && e('0'); // 判断创建的待办数据objectID字段错误，返回结果id为0
dao::getError();
r($todoTest->createTest($todo))                && p() && e('6'); // 判断创建待办，返回结果id为6
r($todoTest->createTest($todoWithCycle))       && p() && e('7'); // 判断创建周期待办，返回结果id为7
