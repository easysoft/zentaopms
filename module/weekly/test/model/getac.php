#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getAC();
timeout=0
cid=19718

- 测试project值为0，date值为2022-05-01 @485.00
- 测试project值为0，date值为空 @485.00
- 测试project值为5，date值为2022-05-01 @414.00
- 测试project值为5，date值为空 @414.00
- 测试project值为10，date值为2022-05-01 @439.00
- 测试project值为10，date值为空 @439.00

*/

$effort = zenData('effort');
$effort->project->range('0,1,2,3,4,5,6,7,8,9,10');
$effort->gen(100);

$projectList = array(0, 5, 10);
$dateList    = array(date('Y-m-d'), '');

$weekly = new weeklyTest();

r($weekly->getACTest($projectList[0], $dateList[0])) && p() && e('485.00'); //测试project值为0，date值为2022-05-01
r($weekly->getACTest($projectList[0], $dateList[1])) && p() && e('485.00'); //测试project值为0，date值为空
r($weekly->getACTest($projectList[1], $dateList[0])) && p() && e('414.00'); //测试project值为5，date值为2022-05-01
r($weekly->getACTest($projectList[1], $dateList[1])) && p() && e('414.00'); //测试project值为5，date值为空
r($weekly->getACTest($projectList[2], $dateList[0])) && p() && e('439.00'); //测试project值为10，date值为2022-05-01
r($weekly->getACTest($projectList[2], $dateList[1])) && p() && e('439.00'); //测试project值为10，date值为空
