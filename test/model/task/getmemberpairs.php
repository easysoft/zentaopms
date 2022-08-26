#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerEstimate();
cid=1
pid=1

获取taskid为1的团队人员 po82 >> P:研发主管82
获取taskid为1的团队人员 user92 >> U:测试92
获取taskid为1的团队人员数量 >> 3
获取taskid为601的团队人员数量 >> 1
获取taskid为不存在的100001的团队人员 >> 0

*/

$taskIDList = array('1', '601', '100001');

$task = new taskTest();
r($task->getMemberPairsTest($taskIDList[0])) && p('po82')   && e('P:研发主管82'); //获取taskid为1的团队人员 po82
r($task->getMemberPairsTest($taskIDList[0])) && p('user92') && e('U:测试92');     //获取taskid为1的团队人员 user92
r($task->getMemberPairsTest($taskIDList[0])) && p('count')  && e('3');            //获取taskid为1的团队人员数量
r($task->getMemberPairsTest($taskIDList[1])) && p('count')  && e('1');            //获取taskid为601的团队人员数量
r($task->getMemberPairsTest($taskIDList[2])) && p()         && e('0');            //获取taskid为不存在的100001的团队人员