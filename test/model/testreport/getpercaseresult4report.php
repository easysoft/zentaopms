#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getPerCaseResult4Report();
cid=1
pid=1

正常查询 >> 通过,1
taskID为空查询 >> 0

*/
$taskID   = array('1', '');
$reportID = '1';

$testreport = new testreportTest();

r($testreport->getPerCaseResult4ReportTest($taskID[0], $reportID)) && p('pass:name,value') && e('通过,1'); //正常查询
r($testreport->getPerCaseResult4ReportTest($taskID[1], $reportID)) && p()                  && e('0');      //taskID为空查询