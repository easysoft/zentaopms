#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getMembers2ImportTest();
cid=1
pid=1

正常数据查询 >> po82,研发
无效数据查询 >> 无数据
正常数据查询统计 >> 2

*/

$executionID    = '101';
$accountList    = array('test7', 'test82');
$allAccountList = array('test82', 'po82', 'user92');
$count          = array('0','1');

$execution = new executionTest();
r($execution->getMembers2ImportTest($executionID, $accountList, $count[0]))    && p('po82:account,role') && e('po82,研发'); // 正常数据查询
r($execution->getMembers2ImportTest($executionID, $allAccountList, $count[0])) && p()                    && e('无数据');    // 无效数据查询
r($execution->getMembers2ImportTest($executionID, $accountList, $count[1]))    && p()                    && e('2');         // 正常数据查询统计