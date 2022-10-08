#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getTotalPercent();
cid=1
pid=1

测试获取阶段131的总进度 >> 60
测试获取阶段131是父阶段的总进度 >> 0
测试获取阶段132的总进度 >> 60
测试获取阶段133的总进度 >> 60
测试获取阶段134的总进度 >> 60
测试获取阶段135的总进度 >> 60

*/

$stageIDList = array(131, 132, 133, 134, 135);

$programplan = new programplanTest();

r($programplan->getTotalPercentTest($stageIDList[0]))       && p() && e('60'); // 测试获取阶段131的总进度
r($programplan->getTotalPercentTest($stageIDList[0], true)) && p() && e('0');  // 测试获取阶段131是父阶段的总进度
r($programplan->getTotalPercentTest($stageIDList[1]))       && p() && e('60'); // 测试获取阶段132的总进度
r($programplan->getTotalPercentTest($stageIDList[2]))       && p() && e('60'); // 测试获取阶段133的总进度
r($programplan->getTotalPercentTest($stageIDList[3]))       && p() && e('60'); // 测试获取阶段134的总进度
r($programplan->getTotalPercentTest($stageIDList[4]))       && p() && e('60'); // 测试获取阶段135的总进度