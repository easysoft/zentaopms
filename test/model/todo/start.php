#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->start();
cid=1
pid=1

开始一个状态为wait的todo >> doing
开始一个状态为doing的todo >> doing
开始一个状态为done的todo >> doing

*/

$todoIDList = array('1', '2', '3');

$todo = new todoTest();

r($todo->startTest($todoIDList[0])) && p('status') && e('doing'); // 开始一个状态为wait的todo
r($todo->startTest($todoIDList[1])) && p('status') && e('doing'); // 开始一个状态为doing的todo
r($todo->startTest($todoIDList[2])) && p('status') && e('doing'); // 开始一个状态为done的todo
