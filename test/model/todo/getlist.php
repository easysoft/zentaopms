#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->getList();
cid=1
pid=1

获取type为today 当前用户的代办数量 >> 0
获取type为yesterday 当前用户的代办数量 >> 0
获取type为thisweek 当前用户的代办数量 >> 0
获取type为lastweek 当前用户的代办数量 >> 1
获取type为thismonth 当前用户的代办数量 >> 1
获取type为lastmonth 当前用户的代办数量 >> 1
获取type为thisseason 当前用户的代办数量 >> 2
获取type为thisyear 当前用户的代办数量 >> 2
获取type为future 当前用户的代办数量 >> 0
获取type为before 当前用户的代办数量 >> 2
获取type为cycle 当前用户的代办数量 >> 1
获取type为today user1的代办数量 >> 0
获取type为yesterday user1的代办数量 >> 0
获取type为thisweek user1的代办数量 >> 0
获取type为lastweek user1的代办数量 >> 1
获取type为thismonth user1的代办数量 >> 1
获取type为lastmonth user1的代办数量 >> 1
获取type为thisseason user1的代办数量 >> 2
获取type为thisyear user1的代办数量 >> 2
获取type为future user1的代办数量 >> 0
获取type为before user1的代办数量 >> 2
获取type为cycle user1的代办数量 >> 1

*/

$typeList = array('today', 'yesterday', 'thisweek', 'lastweek', 'thismonth', 'lastmonth', 'thisseason', 'thisyear', 'future', 'before', 'cycle');
$account  = 'user1';

$todo = new todoTest();

r($todo->getListTest($typeList[0]))            && p() && e('0'); // 获取type为today 当前用户的代办数量
r($todo->getListTest($typeList[1]))            && p() && e('0'); // 获取type为yesterday 当前用户的代办数量
r($todo->getListTest($typeList[2]))            && p() && e('0'); // 获取type为thisweek 当前用户的代办数量
r($todo->getListTest($typeList[3]))            && p() && e('1'); // 获取type为lastweek 当前用户的代办数量
r($todo->getListTest($typeList[4]))            && p() && e('1'); // 获取type为thismonth 当前用户的代办数量
r($todo->getListTest($typeList[5]))            && p() && e('1'); // 获取type为lastmonth 当前用户的代办数量
r($todo->getListTest($typeList[6]))            && p() && e('2'); // 获取type为thisseason 当前用户的代办数量
r($todo->getListTest($typeList[7]))            && p() && e('2'); // 获取type为thisyear 当前用户的代办数量
r($todo->getListTest($typeList[8]))            && p() && e('0'); // 获取type为future 当前用户的代办数量
r($todo->getListTest($typeList[9]))            && p() && e('2'); // 获取type为before 当前用户的代办数量
r($todo->getListTest($typeList[10]))           && p() && e('1'); // 获取type为cycle 当前用户的代办数量
r($todo->getListTest($typeList[0], $account))  && p() && e('0'); // 获取type为today user1的代办数量
r($todo->getListTest($typeList[1], $account))  && p() && e('0'); // 获取type为yesterday user1的代办数量
r($todo->getListTest($typeList[2], $account))  && p() && e('0'); // 获取type为thisweek user1的代办数量
r($todo->getListTest($typeList[3], $account))  && p() && e('1'); // 获取type为lastweek user1的代办数量
r($todo->getListTest($typeList[4], $account))  && p() && e('1'); // 获取type为thismonth user1的代办数量
r($todo->getListTest($typeList[5], $account))  && p() && e('1'); // 获取type为lastmonth user1的代办数量
r($todo->getListTest($typeList[6], $account))  && p() && e('2'); // 获取type为thisseason user1的代办数量
r($todo->getListTest($typeList[7], $account))  && p() && e('2'); // 获取type为thisyear user1的代办数量
r($todo->getListTest($typeList[8], $account))  && p() && e('0'); // 获取type为future user1的代办数量
r($todo->getListTest($typeList[9], $account))  && p() && e('2'); // 获取type为before user1的代办数量
r($todo->getListTest($typeList[10], $account)) && p() && e('1'); // 获取type为cycle user1的代办数量