#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getResultSummary();
cid=1
pid=1

正常查询 >> 共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。
taskID为空查询 >> 共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。

*/
$taskID   = array('1', '');
$reportID = '1';

$testreport = new testreportTest();
r($testreport->getResultSummaryTest($taskID[0], $reportID)) && p() && e('共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。'); //正常查询
r($testreport->getResultSummaryTest($taskID[1], $reportID)) && p() && e('共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。'); //taskID为空查询