#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getPlanBugs();
cid=1
pid=1

查询计划1 状态为all的bug >> BUG3,BUG2,BUG1
查询计划1 状态为active的bug >> BUG3,BUG2,BUG1
查询计划1 状态为resolved的bug >> 0
查询计划1 状态为closed的bug >> 0
查询计划4 状态为all的bug >> BUG6,BUG5,BUG4
查询计划4 状态为active的bug >> BUG6,BUG5,BUG4
查询计划4 状态为resolved的bug >> 0
查询计划4 状态为closed的bug >> 0
查询计划7 状态为all的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
查询计划7 状态为active的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
查询计划7 状态为resolved的bug >> 0
查询计划7 状态为closed的bug >> 0

*/

$planIDList = array('1', '4', '7', '1000001');
$statusList = array('all', 'active', 'resolved', 'closed');

$bug=new bugTest();

r($bug->getPlanBugsTest($planIDList[0], $statusList[0])) && p('title') && e('BUG3,BUG2,BUG1'); // 查询计划1 状态为all的bug
r($bug->getPlanBugsTest($planIDList[0], $statusList[1])) && p('title') && e('BUG3,BUG2,BUG1'); // 查询计划1 状态为active的bug
r($bug->getPlanBugsTest($planIDList[0], $statusList[2])) && p('title') && e('0');              // 查询计划1 状态为resolved的bug
r($bug->getPlanBugsTest($planIDList[0], $statusList[3])) && p('title') && e('0');              // 查询计划1 状态为closed的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[0])) && p('title') && e('BUG6,BUG5,BUG4'); // 查询计划4 状态为all的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[1])) && p('title') && e('BUG6,BUG5,BUG4'); // 查询计划4 状态为active的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[2])) && p('title') && e('0');              // 查询计划4 状态为resolved的bug
r($bug->getPlanBugsTest($planIDList[1], $statusList[3])) && p('title') && e('0');              // 查询计划4 状态为closed的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[0])) && p('title') && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7'); // 查询计划7 状态为all的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[1])) && p('title') && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7'); // 查询计划7 状态为active的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[2])) && p('title') && e('0');              // 查询计划7 状态为resolved的bug
r($bug->getPlanBugsTest($planIDList[2], $statusList[3])) && p('title') && e('0');              // 查询计划7 状态为closed的bug