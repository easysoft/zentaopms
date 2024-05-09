#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 todoTao::getTodoCountByAccount();
timeout=0
cid=1

*/

function initData ()
{
    zenData('todo')->loadYaml('gettodocountbyaccount')->gen(5);
}

initData();

global $tester;
$tester->loadModel('todo')->todoTao;

$account = array('user1', 'admin', 'user2', 'user3');

r($tester->todo->getTodoCountByAccount($account[0])) && p() && e('1'); // 判断用户为user1的待办事项数量为1
r($tester->todo->getTodoCountByAccount($account[1])) && p() && e('1'); // 判断用户为admin的待办事项数量为1
r($tester->todo->getTodoCountByAccount($account[2])) && p() && e('1'); // 判断用户为user2的待办事项数量为1
r($tester->todo->getTodoCountByAccount($account[3])) && p() && e('0'); // 判断用户为user3的待办事项数量为0
