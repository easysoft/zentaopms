#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getPV();
cid=1
pid=1

测试projectID值为0，date值为2022-05-01 >> 1488
测试projectID值为0，date值为空 >> 4223.02
测试projectID值为11，date值为2022-05-01 >> 24
测试projectID值为11，date值为空 >> 100
测试projectID值为41，date值为2022-05-01 >> 9
测试projectID值为41，date值为空 >> 50.5

*/

$projectIDList = array(0, 11, 41);
$dateList      = array('2022-05-01', '');

$weekly = new weeklyTest();

r($weekly->getPVTest($projectIDList[0], $dateList[0])) && p() && e('1488');    //测试projectID值为0，date值为2022-05-01
r($weekly->getPVTest($projectIDList[0], $dateList[1])) && p() && e('4223.02'); //测试projectID值为0，date值为空
r($weekly->getPVTest($projectIDList[1], $dateList[0])) && p() && e('24');      //测试projectID值为11，date值为2022-05-01
r($weekly->getPVTest($projectIDList[1], $dateList[1])) && p() && e('100');     //测试projectID值为11，date值为空
r($weekly->getPVTest($projectIDList[2], $dateList[0])) && p() && e('9');       //测试projectID值为41，date值为2022-05-01
r($weekly->getPVTest($projectIDList[2], $dateList[1])) && p() && e('50.5');    //测试projectID值为41，date值为空