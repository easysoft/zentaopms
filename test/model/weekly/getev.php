#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getEV();
cid=1
pid=1

测试projectID值为0，date值为2022-05-01 >> 771.55
测试projectID值为0，date值为空 >> 2342.78
测试projectID值为11，date值为2022-05-01 >> 15.3
测试projectID值为11，date值为空 >> 54.18
测试projectID值为41，date值为2022-05-01 >> 6.91
测试projectID值为41，date值为空 >> 19.87

*/
$projectIDList = array(0, 11, 41);
$dateList      = array('2022-05-01', '');

$weekly = new weeklyTest();

r($weekly->getEVTest($projectIDList[0], $dateList[0])) && p() && e('771.55');  //测试projectID值为0，date值为2022-05-01
r($weekly->getEVTest($projectIDList[0], $dateList[1])) && p() && e('2342.78'); //测试projectID值为0，date值为空
r($weekly->getEVTest($projectIDList[1], $dateList[0])) && p() && e('15.3');    //测试projectID值为11，date值为2022-05-01
r($weekly->getEVTest($projectIDList[1], $dateList[1])) && p() && e('54.18');   //测试projectID值为11，date值为空
r($weekly->getEVTest($projectIDList[2], $dateList[0])) && p() && e('6.91');    //测试projectID值为41，date值为2022-05-01
r($weekly->getEVTest($projectIDList[2], $dateList[1])) && p() && e('19.87');   //测试projectID值为41，date值为空