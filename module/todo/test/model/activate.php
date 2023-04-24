#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

/**

title=测试 todoModel->activate();
cid=1
pid=1

激活一个状态为wait的todo >> wait
激活一个状态为doing的todo >> wait
激活一个状态为done的todo >> wait

*/

$todoIDList = array('1', '2', '3');

$todo = new todoTest();

r($todo->activateTest($todoIDList[0])) && p('status') && e('wait'); // 激活一个状态为wait的todo
r($todo->activateTest($todoIDList[1])) && p('status') && e('wait'); // 激活一个状态为doing的todo
r($todo->activateTest($todoIDList[2])) && p('status') && e('wait'); // 激活一个状态为done的todo
