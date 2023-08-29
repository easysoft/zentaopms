#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('project')->gen(10);
zdTable('kanbanlane')->config('kanbanlane')->gen(10);

/**

title=测试 executionModel->getLaneMaxEditedTime();
timeout=0
cid=1

*/

$executionIdList = array(2, 3, 4);

global $tester;
$executionModel = $tester->loadModel('execution');
r($tester->execution->getLaneMaxEditedTime($executionIdList[0])) && p() && e('2022-01-07 00:00:00'); // 获取迭代看板的最后更新时间
r($tester->execution->getLaneMaxEditedTime($executionIdList[1])) && p() && e('2022-01-08 00:00:00'); // 获取迭代看板的最后更新时间
r($tester->execution->getLaneMaxEditedTime($executionIdList[2])) && p() && e('2022-01-09 00:00:00'); // 获取迭代看板的最后更新时间
