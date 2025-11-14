#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

/**

title=测试 todoModel->activate();
timeout=0
cid=19245

- 激活一个状态为wait的todo属性status @wait
- 激活一个状态为doing的todo属性status @wait
- 激活一个状态为done的todo属性status @wait
- 激活一个状态为closed的todo属性status @wait
- 激活一个状态为wait的todo属性status @wait

*/

su('admin');

zenData('todo')->gen(5);

$todoIDList = array('1', '2', '3');

$todo = new todoTest();

r($todo->activateTest(1)) && p('status') && e('wait'); // 激活一个状态为wait的todo
r($todo->activateTest(2)) && p('status') && e('wait'); // 激活一个状态为doing的todo
r($todo->activateTest(3)) && p('status') && e('wait'); // 激活一个状态为done的todo
r($todo->activateTest(4)) && p('status') && e('wait'); // 激活一个状态为closed的todo
r($todo->activateTest(5)) && p('status') && e('wait'); // 激活一个状态为wait的todo
