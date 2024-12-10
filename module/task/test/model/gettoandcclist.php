#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('task')->loadYaml('task')->gen(9);
zenData('taskteam')->loadYaml('taskteam')->gen(5);
su('admin');

/**

title=taskModel->getToAndCcList();
cid=1
pid=1

*/
$taskIDList = array('1', '2', '3', '4', '5', '7', '10');

$task = new taskTest();
$task4Result = $task->getToAndCcListTest($taskIDList[3]);
r(count($task->getToAndCcListTest($taskIDList[0]), true)) && p()      && e('2');           //计算无assignedto 无mailto的发信列表
r($task->getToAndCcListTest($taskIDList[1]))              && p('0')   && e('admin');       //计算有assignedto 无mailto的发信列表
r($task->getToAndCcListTest($taskIDList[2]))              && p('0,1') && e('admin,user1'); //计算有assignedto 有mailto的发信列表
r($task4Result[0])                                        && p()      && e('user2');       //计算无assignedto 多mailto的发信列表的toList
r($task4Result[1])                                        && p()      && e('admin,user1'); //计算无assignedto 多mailto的发信列表的ccList
r($task->getToAndCcListTest($taskIDList[4]))              && p('0')   && e('user3');       //计算无assignedto 单个mailto的发信列表
r($task->getToAndCcListTest($taskIDList[6]))              && p()      && e('0');           //计算不存在的task的发信列表
