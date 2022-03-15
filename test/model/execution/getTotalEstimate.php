#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTotalEstimateTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$executionIDList = array('101', '131', '161');

$execution = new executionTest();
r($execution->getTotalEstimateTest($executionIDList[0])) && p() && e('48'); // 敏捷执行预计工时统计
r($execution->getTotalEstimateTest($executionIDList[1])) && p() && e('17'); // 瀑布执行预计工时统计
r($execution->getTotalEstimateTest($executionIDList[2])) && p() && e('20'); // 看板执行预计工时统计
r($execution->getTotalEstimateTest(''))                  && p() && e('0');  // 无执行预计工时统计
