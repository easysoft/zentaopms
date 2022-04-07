#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getTaskCases();
cid=1
pid=1

正常查询 >> 1;2
tasksID为空查询 >> 0
idList为空查询 >> 1;3

*/
$tasksID  = array('1', '');
$reportID = '1';
$idList   = array('1,2', '');

$testreport = new testreportTest();

r($testreport->getTaskCasesTest($tasksID[0], $reportID, $idList[0])[1]) && p('1:id;2:id') && e('1;2'); //正常查询
r($testreport->getTaskCasesTest($tasksID[1], $reportID, $idList[0]))    && p()            && e('0'); //tasksID为空查询
r($testreport->getTaskCasesTest($tasksID[0], $reportID, $idList[1])[1]) && p('1:id;3:id') && e('1;3'); //idList为空查询