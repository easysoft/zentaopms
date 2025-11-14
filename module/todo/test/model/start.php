#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**

title=测试 todoModel->start();
timeout=0
cid=19266

- 开启一个状态为wait的todo属性status @doing
- 开启一个状态为doing的todo属性status @doing
- 开启一个状态为done的todo属性status @doing
- 开启一个状态为closed的todo属性status @doing
- 开启一个状态为wait的todo属性status @doing

*/

zenData('todo')->loadYaml('start')->gen(5);

$todo = new todoTest();

r($todo->startTest(1)) && p('status') && e('doing'); //开启一个状态为wait的todo
r($todo->startTest(2)) && p('status') && e('doing'); //开启一个状态为doing的todo
r($todo->startTest(3)) && p('status') && e('doing'); //开启一个状态为done的todo
r($todo->startTest(4)) && p('status') && e('doing'); //开启一个状态为closed的todo
r($todo->startTest(5)) && p('status') && e('doing'); //开启一个状态为wait的todo
