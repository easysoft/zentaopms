#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->finish();
cid=1
pid=1

结束一个状态为wait的todo >> done
结束一个状态为doing的todo >> done
结束一个状态为done的todo >> done

*/

$todoIDList = array('1', '2', '3');

$todo = new todoTest();

r($todo->finishTest($todoIDList[0])) && p('status') && e('done'); // 结束一个状态为wait的todo
r($todo->finishTest($todoIDList[1])) && p('status') && e('done'); // 结束一个状态为doing的todo
r($todo->finishTest($todoIDList[2])) && p('status') && e('done'); // 结束一个状态为done的todo
