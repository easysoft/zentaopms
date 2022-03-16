#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTeamMembersTest();
cid=1
pid=1

敏捷执行查看team >> 101,execution,482
瀑布执行查看team >> 131,execution,开发22
看板执行查看team >> 161,execution,开发52
敏捷执行team统计 >> 2
瀑布执行team统计 >> 1
看板执行team统计 >> 1

*/

$executionIDList = array('101', '131', '161');
$count           = array('0','1');

$execution = new executionTest();
r($execution->getTeamMembersTest($executionIDList[0], $count[0])) && p('po82:root,type,userID')     && e('101,execution,482');    // 敏捷执行查看team
r($execution->getTeamMembersTest($executionIDList[1], $count[0])) && p('test22:root,type,realname') && e('131,execution,开发22'); // 瀑布执行查看team
r($execution->getTeamMembersTest($executionIDList[2], $count[0])) && p('test52:root,type,realname') && e('161,execution,开发52'); // 看板执行查看team
r($execution->getTeamMembersTest($executionIDList[0], $count[1])) && p()                            && e('2');                    // 敏捷执行team统计
r($execution->getTeamMembersTest($executionIDList[1], $count[1])) && p()                            && e('1');                    // 瀑布执行team统计
r($execution->getTeamMembersTest($executionIDList[2], $count[1])) && p()                            && e('1');                    // 看板执行team统计