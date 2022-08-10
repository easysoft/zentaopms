#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->getCount();
cid=1
pid=1

获取用户admin的所有待办个数 >> 2
获取用户user1的所有待办个数 >> 2
获取用户user2的所有待办个数 >> 2
获取用户user3的所有待办个数 >> 2
获取不存在的用户所有待办个数 >> 0

*/

$accountList = array('admin', 'user1', 'user2', 'user3', 'user10001');

$todo = new todoTest();

r($todo->getCountTest($accountList[0])) && p() && e('2'); // 获取用户admin的所有待办个数
r($todo->getCountTest($accountList[1])) && p() && e('2'); // 获取用户user1的所有待办个数
r($todo->getCountTest($accountList[2])) && p() && e('2'); // 获取用户user2的所有待办个数
r($todo->getCountTest($accountList[3])) && p() && e('2'); // 获取用户user3的所有待办个数
r($todo->getCountTest($accountList[4])) && p() && e('0'); // 获取不存在的用户所有待办个数