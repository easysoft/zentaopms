#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getAC();
cid=1
pid=1

测试project值为0，date值为2022-05-01 >> 4191
测试project值为0，date值为空 >> 4191
测试project值为11，date值为2022-05-01 >> 21
测试project值为11，date值为空 >> 21
测试project值为41，date值为2022-05-01 >> 42
测试project值为41，date值为空 >> 42

*/
$projectList = array(0, 11, 41);
$dateList    = array('2022-05-01', '');

$weekly = new weeklyTest();

r($weekly->getACTest($projectList[0], $dateList[0])) && p() && e('4191'); //测试project值为0，date值为2022-05-01
r($weekly->getACTest($projectList[0], $dateList[1])) && p() && e('4191'); //测试project值为0，date值为空
r($weekly->getACTest($projectList[1], $dateList[0])) && p() && e('21');   //测试project值为11，date值为2022-05-01
r($weekly->getACTest($projectList[1], $dateList[1])) && p() && e('21');   //测试project值为11，date值为空
r($weekly->getACTest($projectList[2], $dateList[0])) && p() && e('42');   //测试project值为41，date值为2022-05-01
r($weekly->getACTest($projectList[2], $dateList[1])) && p() && e('42');   //测试project值为41，date值为空