#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getPVEV();
cid=1
pid=1

测试projectID值为0，date值为2022-05-01 >> 0.00,0.00
测试projectID值为0，date值为空 >> 0.00,0.00
测试projectID值为11，date值为2022-05-01 >> 0.00,0.00
测试projectID值为11，date值为空 >> 6.00,1.98
测试projectID值为41，date值为2022-05-01 >> 0.00,0.00
测试projectID值为41，date值为空 >> 14.50,3.43

*/

$projectIDList = array(0, 11, 41);
$dateList      = array('2022-05-01', '');

$weekly = new weeklyTest();

r($weekly->getPVEVTest($projectIDList[0], $dateList[0])) && p() && e('0.00,0.00');    //测试projectID值为0，date值为2022-05-01
r($weekly->getPVEVTest($projectIDList[0], $dateList[1])) && p() && e('0.00,0.00');    //测试projectID值为0，date值为空
r($weekly->getPVEVTest($projectIDList[1], $dateList[0])) && p() && e('0.00,0.00');    //测试projectID值为11，date值为2022-05-01
r($weekly->getPVEVTest($projectIDList[1], $dateList[1])) && p() && e('6.00,1.98');    //测试projectID值为11，date值为空
r($weekly->getPVEVTest($projectIDList[2], $dateList[0])) && p() && e('0.00,0.00');    //测试projectID值为41，date值为2022-05-01
r($weekly->getPVEVTest($projectIDList[2], $dateList[1])) && p() && e('14.50,3.43');   //测试projectID值为41，date值为空
