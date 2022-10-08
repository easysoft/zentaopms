#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getBug4Report();
cid=1
pid=1

正常查询 >> 0;0
taskIDs为空查询 >> 0;0
productIdList为空查询 >> 0;0
buildIDs为空查询 >> 0;0

*/
$taskIDs       = array('1');
$productIdList = array('1');
$reportID      = '1';
$buildIDs      = array('11');

$testreport = new testreportTest();
r($testreport->getBug4ReportTest($taskIDs, $productIdList, $reportID, $buildIDs)) && p('1:foundBugs;1:countBugByTask') && e('0;0');//正常查询
r($testreport->getBug4ReportTest(array(),  $productIdList, $reportID, $buildIDs)) && p('1:foundBugs;1:countBugByTask') && e('0;0');//taskIDs为空查询
r($testreport->getBug4ReportTest($taskIDs, array(),        $reportID, $buildIDs)) && p('1:foundBugs;1:countBugByTask') && e('0;0');//productIdList为空查询
r($testreport->getBug4ReportTest($taskIDs, $productIdList, $reportID, array()))   && p('1:foundBugs;1:countBugByTask') && e('0;0');//buildIDs为空查询