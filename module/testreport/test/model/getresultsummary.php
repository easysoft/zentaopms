#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';

zdTable('case')->gen(10);
zdTable('testtask')->gen(10);
zdTable('testrun')->gen(10);
zdTable('testresult')->gen(10);
zdTable('testreport')->gen(10);

su('admin');

/**

title=测试 testreportModel->getResultSummary();
timeout=0
cid=1

- 正常查询 @共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。
- taskID为空查询 @共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。

*/
$taskID   = array(1, 0);
$reportID = 1;

$testreport = new testreportTest();
r($testreport->getResultSummaryTest($taskID[0], $reportID)) && p() && e('共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。'); //正常查询
r($testreport->getResultSummaryTest($taskID[1], $reportID)) && p() && e('共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。'); //taskID为空查询
