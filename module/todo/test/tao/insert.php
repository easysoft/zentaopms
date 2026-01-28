#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

/**

title=测试 todoTao::insert();
timeout=0
cid=19280

- 正常插入待办数据 @2
- 正常插入task类型待办数据 @3
- 插入没有名称的待办数据 @3
- 插入bug类型待办，没有bugID的情况 @3
- 正常插入待办数据 @4

*/

zenData('todo')->gen(1);

$todoTester = new todoTaoTest();

$todo = new stdclass();
$todo->account = 'admin';
$todo->date         = date('Y-m-d');
$todo->begin        = '1000';
$todo->end          = '1400';
$todo->type         = 'custom';
$todo->name         = '我的待办名字';
$todo->desc         = '我的待办描述';
$todo->status       = 'wait';
$todo->feedback     = '0';
$todo->finishedDate = date('Y-m-d H:i:s');
$todo->assignedDate = date('Y-m-d H:i:s');
$todo->closedDate   = date('Y-m-d H:i:s');

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

r($todoTester->insertTest($todo))       && p() && e('2'); // 正常插入待办数据
r($todoTester->insertTest($todoTask))   && p() && e('3'); // 正常插入task类型待办数据

r($todoTester->insertTest($todoNoName)) && p('name:0')     && e('『待办名称』不能为空。'); // 插入没有名称的待办数据
r($todoTester->insertTest($todoBug))    && p('objectID:0') && e('『关联编号』不能为空。'); // 插入bug类型待办，没有bugID的情况

$todo->name = '测试待办';
r($todoTester->insertTest($todo))       && p() && e('4'); // 正常插入待办数据
