#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->batchUpdate();
cid=1
pid=1

批量修改todo类型 >> type,task,custom
批量修改todo优先级 >> pri,2,1
批量修改todo状态 >> status,wait,doing

*/

$types      = array('1' => 'custom', '2' => 'bug', '3' => 'custom');
$pris       = array('1' => '1', '2' => '1', '3' => '3');
$status     = array('1' => 'doing', '2' => 'doing', '3' => 'done');
$bugs       = array('2' => '1');
$tasks      = array('3' => '2');

$changeType   = array('types' => $types, 'bugs' => $bugs);
$changePri    = array('pris' => $pris, 'bugs' => $bugs, 'tasks' => $tasks);
$changeStatus = array('status' => $status, 'bugs' => $bugs, 'tasks' => $tasks);

$todo = new todoTest();

r($todo->batchUpdateTest($changeType, '3'))   && p('0:field,old,new') && e('type,task,custom');   // 批量修改todo类型
r($todo->batchUpdateTest($changePri, '2'))    && p('0:field,old,new') && e('pri,2,1');            // 批量修改todo优先级
r($todo->batchUpdateTest($changeStatus, '1')) && p('0:field,old,new') && e('status,wait,doing');  // 批量修改todo状态
