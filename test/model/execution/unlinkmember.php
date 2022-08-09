#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->unlinkMemberTest();
cid=1
pid=1

敏捷执行解除团队成员 >> po82,研发
敏捷执行解除团队成员后统计 >> 1
瀑布执行解除团队成员 >> 0
看板执行解除团队成员 >> test52,测试
看板执行解除团队成员后统计 >> 1

*/

$accountList     = array('user92', 'test22', 'test52');
$executionIDList = array('101', '131', '161');
$count           = array('0','1');

$execution = new executionTest();
r($execution->unlinkMemberTest($executionIDList[0], $accountList[0], $count[0])) && p('0:account,role') && e('po82,研发');   // 敏捷执行解除团队成员
r($execution->unlinkMemberTest($executionIDList[0], $accountList[0], $count[1])) && p()                 && e('1');           // 敏捷执行解除团队成员后统计
r($execution->unlinkMemberTest($executionIDList[1], $accountList[1], $count[0])) && p()                 && e('0');           // 瀑布执行解除团队成员
r($execution->unlinkMemberTest($executionIDList[2], $accountList[2], $count[0])) && p('0:account,role') && e('test52,测试'); // 看板执行解除团队成员
r($execution->unlinkMemberTest($executionIDList[2], $accountList[2], $count[1])) && p()                 && e('1');           // 看板执行解除团队成员后统计
$db->restoreDB();