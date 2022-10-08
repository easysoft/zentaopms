#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getMembersByIdListTest();
cid=1
pid=1

批量查询敏捷执行team >> 101,po82,研发主管82
批量查询瀑布执行team >> 131,test22,开发22
批量查询看板执行team >> 161,test52,开发52
批量查询执行team统计 >> 3

*/

$executionIDList = array('101', '131', '161');
$count           = array('0','1');

$execution = new executionTest();
r($execution->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[0]]) && p('0:root,account,realname') && e('101,po82,研发主管82');// 批量查询敏捷执行team
r($execution->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[1]]) && p('0:root,account,realname') && e('131,test22,开发22');  // 批量查询瀑布执行team
r($execution->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[2]]) && p('0:root,account,realname') && e('161,test52,开发52');  // 批量查询看板执行team
r($execution->getMembersByIdListTest($executionIDList, $count[1]))                      && p()                          && e('3');                  // 批量查询执行team统计