#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**

title=测试 todoModel->activate();
timeout=0
cid=1

- 激活一个状态为wait的todo属性status @wait

- 激活一个状态为doing的todo属性status @wait

- 激活一个状态为done的todo属性status @wait



*/

function initData()
{
    zenData('todo')->loadYaml('activate')->gen(3);
}

initData();

$todoIDList = array('1', '2', '3');

$todo = new todoTest();

r($todo->activateTest($todoIDList[0])) && p('status') && e('wait'); // 激活一个状态为wait的todo
r($todo->activateTest($todoIDList[1])) && p('status') && e('wait'); // 激活一个状态为doing的todo
r($todo->activateTest($todoIDList[2])) && p('status') && e('wait'); // 激活一个状态为done的todo
