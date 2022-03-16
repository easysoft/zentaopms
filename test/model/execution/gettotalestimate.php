#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTotalEstimateTest();
cid=1
pid=1

敏捷执行预计工时统计 >> 48
瀑布执行预计工时统计 >> 17
看板执行预计工时统计 >> 20
无执行预计工时统计 >> 0

*/

$executionIDList = array('101', '131', '161');

$execution = new executionTest();
r($execution->getTotalEstimateTest($executionIDList[0])) && p() && e('48'); // 敏捷执行预计工时统计
r($execution->getTotalEstimateTest($executionIDList[1])) && p() && e('17'); // 瀑布执行预计工时统计
r($execution->getTotalEstimateTest($executionIDList[2])) && p() && e('20'); // 看板执行预计工时统计
r($execution->getTotalEstimateTest(''))                  && p() && e('0');  // 无执行预计工时统计