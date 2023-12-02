#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('user')->gen(1);
zdTable('bug')->gen(20);
zdTable('productplan')->gen(10);

su('admin');

/**

title=bugModel->getPlanBugs();
cid=1
pid=1

*/

$planIDList = array(1, 4, 7, 1000001);
$statusList = array('all', 'active', 'resolved', 'closed');

$bug=new bugTest();

r($bug->getPlanBugsTest($planIDList[0], $statusList[0])) && p('title', '-') && e('BUG3,BUG2,BUG1'); // 查询计划1 状态为all的bug
r($bug->getPlanBugsTest($planIDList[0], $statusList[1])) && p('title', '-') && e('BUG3,BUG2,BUG1'); // 查询计划1 状态为active的bug
r($bug->getPlanBugsTest($planIDList[0], $statusList[2])) && p('title', '-') && e('0');              // 查询计划1 状态为resolved的bug
r($bug->getPlanBugsTest($planIDList[0], $statusList[3])) && p('title', '-') && e('0');              // 查询计划1 状态为closed的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[0])) && p('title', '-') && e('BUG6,BUG5,BUG4'); // 查询计划4 状态为all的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[1])) && p('title', '-') && e('BUG6,BUG5,BUG4'); // 查询计划4 状态为active的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[2])) && p('title', '-') && e('0');              // 查询计划4 状态为resolved的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[3])) && p('title', '-') && e('0');              // 查询计划4 状态为closed的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[0])) && p('title', '-') && e('BUG9,bug8,缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）7'); // 查询计划7 状态为all的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[1])) && p('title', '-') && e('BUG9,bug8,缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）7'); // 查询计划7 状态为active的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[2])) && p('title', '-') && e('0');              // 查询计划7 状态为resolved的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[3])) && p('title', '-') && e('0');              // 查询计划7 状态为closed的bug
