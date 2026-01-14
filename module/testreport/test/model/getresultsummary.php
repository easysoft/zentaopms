#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen(10);
zenData('testtask')->gen(10);
zenData('testrun')->gen(10);
zenData('testresult')->gen(10);
zenData('testreport')->gen(10);

su('admin');

/**

title=测试 testreportModel->getResultSummary();
timeout=0
cid=19122

- 正常查询 @共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。
- taskID为空查询 @共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。
- taskID为空查询 @共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。
- taskID为空查询 @共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。
- taskID为空查询 @共有<strong>2</strong>个用例，共执行<strong>2</strong>个用例，产生了<strong>2</strong>个结果，失败的用例有<strong>1</strong>个。

*/

$testreport = new testreportModelTest();
r($testreport->getResultSummaryTest(1, 1)) && p() && e('共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。'); //正常查询
r($testreport->getResultSummaryTest(0, 1)) && p() && e('共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。'); //taskID为空查询
r($testreport->getResultSummaryTest(2, 2)) && p() && e('共有<strong>4</strong>个用例，共执行<strong>4</strong>个用例，产生了<strong>4</strong>个结果，失败的用例有<strong>2</strong>个。'); //taskID为空查询
r($testreport->getResultSummaryTest(0, 2)) && p() && e('共有<strong>0</strong>个用例，共执行<strong>0</strong>个用例，产生了<strong>0</strong>个结果，失败的用例有<strong>0</strong>个。'); //taskID为空查询
r($testreport->getResultSummaryTest(3, 3)) && p() && e('共有<strong>2</strong>个用例，共执行<strong>2</strong>个用例，产生了<strong>2</strong>个结果，失败的用例有<strong>1</strong>个。'); //taskID为空查询