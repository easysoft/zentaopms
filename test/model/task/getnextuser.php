#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerEstimate();
cid=1
pid=1

计算current为0的下一个用户 >> user1
计算current为1的下一个用户 >> user2
计算current为2的下一个用户 >> po1
计算current为3的下一个用户 >> po2
计算current为4的下一个用户 >> pm1
计算current为5的下一个用户 >> pm2
计算current为6的下一个用户 >> admin

*/

$users = array('admin', 'user1', 'user2', 'po1', 'po2', 'pm1', 'pm2');

$task = new taskTest();
r($task->getNextUserTest($users, $users[0])) && p() && e('user1'); //计算current为0的下一个用户
r($task->getNextUserTest($users, $users[1])) && p() && e('user2'); //计算current为1的下一个用户
r($task->getNextUserTest($users, $users[2])) && p() && e('po1');   //计算current为2的下一个用户
r($task->getNextUserTest($users, $users[3])) && p() && e('po2');   //计算current为3的下一个用户
r($task->getNextUserTest($users, $users[4])) && p() && e('pm1');   //计算current为4的下一个用户
r($task->getNextUserTest($users, $users[5])) && p() && e('pm2');   //计算current为5的下一个用户
r($task->getNextUserTest($users, $users[6])) && p() && e('admin'); //计算current为6的下一个用户