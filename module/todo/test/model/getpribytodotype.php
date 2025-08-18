#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

su('admin');

function initData()
{
    zenData('todo')->loadYaml('todo')->gen(10);
    zenData('bug')->gen(10);
    zenData('task')->gen(10);
    zenData('story')->gen(10);
    zenData('testtask')->gen(10);
}

/**

title=测试 todoModel->getPriByTodoType();
timeout=0
cid=1

- 获取 bug 的优先级 @1
- 获取 bug 的优先级 @2
- 获取 task 的优先级 @3
- 获取 story 的优先级 @4
- 获取 testtask 的优先级 @1

*/

initData();

global $tester;
$tester->loadModel('todo');
r($tester->todo->getPriByTodoType('bug', 1))       && p() && e('1'); // 获取 bug 的优先级
r($tester->todo->getPriByTodoType('bug', 2))       && p() && e('2'); // 获取 bug 的优先级
r($tester->todo->getPriByTodoType('task', 3))      && p() && e('3'); // 获取 task 的优先级
r($tester->todo->getPriByTodoType('story', 4))     && p() && e('4'); // 获取 story 的优先级
r($tester->todo->getPriByTodoType('testtask', 5))  && p() && e('1'); // 获取 testtask 的优先级