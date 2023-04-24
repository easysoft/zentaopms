#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

/**

title=测试 todoModel->close();
cid=1
pid=1

关闭一个状态为wait的todo >> closed
关闭一个状态为doing的todo >> closed
关闭一个状态为done的todo >> closed

*/

$todoIDList = array('1', '2', '3');

$todo = new todoTest();

r($todo->closeTest($todoIDList[0])) && p('status') && e('closed'); // 关闭一个状态为wait的todo
r($todo->closeTest($todoIDList[1])) && p('status') && e('closed'); // 关闭一个状态为doing的todo
r($todo->closeTest($todoIDList[2])) && p('status') && e('closed'); // 关闭一个状态为done的todo
