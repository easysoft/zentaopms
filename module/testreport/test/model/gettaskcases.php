#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreport.unittest.class.php';

zenData('testreport')->gen(2);
zenData('testtask')->gen(30);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getTaskCases();
timeout=0
cid=19124

- 正常查询
 - 第1条的id属性 @1
 - 第1条的lastRunResult属性 @pass
 - 第2条的id属性 @2
 - 第2条的lastRunResult属性 @fail
- idList为空查询
 - 第1条的id属性 @1
 - 第2条的id属性 @2
 - 第3条的id属性 @3
 - 第4条的id属性 @4
- tasksID为空查询 @0

*/
$taskIdList = array(1, 0);
$reportID   = 1;
$idList     = array('1,2', '');

$testreport = new testreportTest();

r($testreport->getTaskCasesTest($taskIdList[0], $reportID, $idList[0])[1]) && p('1:id;1:lastRunResult;2:id;2:lastRunResult') && e('1;pass;2;fail'); //正常查询
r($testreport->getTaskCasesTest($taskIdList[0], $reportID, $idList[1])[1]) && p('1:id;2:id;3:id;4:id')                       && e('1;2;3;4');       //idList为空查询
r($testreport->getTaskCasesTest($taskIdList[1], $reportID, $idList[0]))    && p()                                            && e('0');             //tasksID为空查询
