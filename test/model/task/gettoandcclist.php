#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getToAndCcList();
cid=1
pid=1

计算有assignedto 有mailto的发信列表2 >> po82
计算有assignedto 有mailto的发信列表1 >> user1,user2,user3
计算有assignedto 无mailto的发信列表2 >> po82
计算有assignedto 无mailto的发信列表1 >> 0
计算无assignedto 无mailto的发信列表 >> 0
计算不存在的task的发信列表 >> 0

*/
$taskIDList = array('1', '601', '100001');

$task = new taskTest();
r($task->getToAndCcListTest($taskIDList[0]))       && p('2') && e('po82');              //计算有assignedto 有mailto的发信列表2
r($task->getToAndCcListTest($taskIDList[0]))       && p('1') && e('user1,user2,user3'); //计算有assignedto 有mailto的发信列表1
r($task->getToAndCcListTest($taskIDList[0], true)) && p('2') && e('po82');              //计算有assignedto 无mailto的发信列表2
r($task->getToAndCcListTest($taskIDList[0], true)) && p('1') && e('0');                  //计算有assignedto 无mailto的发信列表1
r($task->getToAndCcListTest($taskIDList[1]))       && p()    && e('0');                 //计算无assignedto 无mailto的发信列表
r($task->getToAndCcListTest($taskIDList[2]))       && p()    && e('0');                 //计算不存在的task的发信列表