#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';

su('admin');

function initData()
{
    zdTable('todo')->config('todo')->gen(10);
    zdTable('bug')->gen(10);
    zdTable('task')->gen(10);
    zdTable('story')->gen(10);
    zdTable('testtask')->gen(10);
}

/**

title=测试 todoModel->getPriByTodoType();
timeout=0
cid=1

- 执行todoTest模块的getPriByTodoTypeTest方法，参数是$type, $objectID @1

- 执行todoTest模块的getPriByTodoTypeTest方法，参数是$inValidType, $inValidObjectID @1

*/

initData();

$validTypeList     = array('bug','task','story','testtask');
$validObjectIdList = array_combine(range(1,10), range(1,10));
$type              = $validTypeList[array_rand($validTypeList, 1)];
$objectID          = $validObjectIdList[array_rand($validObjectIdList, 1)];
$inValidType       = 'invalidType';
$inValidObjectID   = 0;

$todoTest = new todoTest();
r($todoTest->getPriByTodoTypeTest($type, $objectID))               && p() && e('1');
r($todoTest->getPriByTodoTypeTest($inValidType, $inValidObjectID)) && p() && e('1');
