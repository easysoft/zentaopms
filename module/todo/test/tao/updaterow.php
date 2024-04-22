#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData ()
{
    zenData('todo')->gen(1);
}

/**

title=测试 todoTao::updateRow();
timeout=0
cid=1

- 正常更新待办数据 @1

- 正常更新task类型待办数据 @1

- 更新没有名称的待办数据 @0

- 更新bug类型待办，没有bugID的情况 @0

*/

initData();

global $tester;
$tester->loadModel('todo');

$todo = new stdclass();
$todo->account = 'admin';
$todo->date   = date('Y-m-d');
$todo->begin  = '1000';
$todo->end    = '1400';
$todo->type   = 'custom';
$todo->name   = '我的待办名字';
$todo->desc   = '我的待办描述';
$todo->status = 'wait';

$todoNoName = clone $todo;
$todoNoName->name = '';

$todoBug = clone $todo;
$todoBug->type     = 'bug';
$todoBug->name     = 'BUG待办';
$todoBug->objectID = 0;

$todoTask = clone $todo;
$todoTask->type     = 'task';
$todoTask->name     = 'TASK待办';
$todoTask->objectID = 1;

r($tester->todo->updateRow(1, $todo))       && p() && e('1'); // 正常更新待办数据
r($tester->todo->updateRow(2, $todoTask))   && p() && e('1'); // 正常更新task类型待办数据
r($tester->todo->updateRow(3, $todoNoName)) && p() && e('0'); // 更新没有名称的待办数据
r($tester->todo->updateRow(4, $todoBug))    && p() && e('0'); // 更新bug类型待办，没有bugID的情况