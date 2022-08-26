#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->importBugTest();
cid=1
pid=1

预计输入错误 >> 最初预计"必须为数字
Bug转任务统计 >> 4

*/

$executionIDList = array('101', '131', '161');
$import          = array('273' => '273', '3' => '3', '2' => '2', '1' => '1');
$id              = array('273' => '273', '3' => '3', '2' => '2', '1' => '1');
$pri             = array('273' => '1', '3' => '1', '2' => '2', '1' => '2');
$errorestimate   = array('273' => '2020-03-01', '3' => '2020-03-02', '2' => '2020-03-02', '1' => '2020-03-03');
$estimate        = array('273' => '7', '3' => '6', '2' => '5', '1' => '4');
$deadline        = array('273' => '2020-03-17', '3' => '2020-03-17', '2' => '2020-03-18', '1' => '2020-03-19');
$count           = array('0','1');

$errorimport = array('import' => $import, 'id' => $id, 'pri' => $pri, 'estimate' => $errorestimate, 'deadline' => $deadline);
$importBugs  = array('import' => $import, 'id' => $id, 'pri' => $pri, 'estimate' => $estimate, 'deadline' => $deadline);

$execution = new executionTest();
r($execution->importBugTest($executionIDList[0], $count[0], $errorimport)) && p('message:0') && e('最初预计"必须为数字');  // 预计输入错误
r($execution->importBugTest($executionIDList[0], $count[1], $importBugs))  && p()            && e('4');                    // Bug转任务统计

$db->restoreDB();