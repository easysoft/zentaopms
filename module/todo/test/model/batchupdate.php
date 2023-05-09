#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    zdTable('todo')->config('batchupdate')->gen(5);
}

/**

title=测试 todoModel->batchUpdate();
timeout=0
cid=1

*/

initData();

$todos = array();
$todos[1] = new stdClass();
$todos[2] = new stdClass();
$todos[3] = new stdClass();
$changeType = $todos;
$changeType[1]->type     = 'bug';
$changeType[1]->objectID = 1;

$todos = array();
$todos[1] = new stdClass();
$todos[2] = new stdClass();
$todos[3] = new stdClass();
$changePri = $todos;
$changePri[2]->pri = 1;

$todos = array();
$todos[1] = new stdClass();
$todos[2] = new stdClass();
$todos[3] = new stdClass();
$changeStatus = $todos;
$changeStatus[3]->status = 'doing';

$todo = new todoTest();
r($todo->batchUpdateTest($changeType, 1))   && p('0:field,old,new') && e('type,custom,bug');   // 批量修改todo类型
r($todo->batchUpdateTest($changePri, 2))    && p('0:field,old,new') && e('pri,3,1');           // 批量修改todo优先级
r($todo->batchUpdateTest($changeStatus, 3)) && p('0:field,old,new') && e('status,wait,doing'); // 批量修改todo状态