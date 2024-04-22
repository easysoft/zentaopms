#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('task')->loadYaml('task')->gen(5);
zenData('taskteam')->loadYaml('taskteam')->gen(5);
su('admin');

/**

title=taskModel->getToAndCcList();
cid=1
pid=1

*/
$taskIDList = array('1', '2', '3', '4', '5', '10');

$task = new taskTest();
r(count($task->getToAndCcListTest($taskIDList[0]), true)) && p()      && e('2');           //计算无assignedto 无mailto的发信列表
r($task->getToAndCcListTest($taskIDList[1]))              && p('0')   && e('admin');       //计算有assignedto 无mailto的发信列表
r($task->getToAndCcListTest($taskIDList[2]))              && p('0,1') && e('admin,user1'); //计算有assignedto 有mailto的发信列表
r($task->getToAndCcListTest($taskIDList[3]))              && p('0,1') && e('admin,user1'); //计算无assignedto 多mailto的发信列表
r($task->getToAndCcListTest($taskIDList[4]))              && p('0')   && e('user1');       //计算无assignedto 单个mailto的发信列表
r($task->getToAndCcListTest($taskIDList[5]))              && p()      && e('0');           //计算不存在的task的发信列表
