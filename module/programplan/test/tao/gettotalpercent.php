#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getTotalPercent();
cid=1
pid=1

*/


zdTable('project')->config('project')->gen(5);
zdTable('projectproduct')->config('projectproduct')->gen(5);

$stageIDList = array(2, 1, 5, 3);

$programplan = new programplanTest();

r($programplan->getTotalPercentTest($stageIDList[0]))        && p() && e('23'); // 测试获取阶段2的总进度
r($programplan->getTotalPercentTest($stageIDList[0], true))  && p() && e('23'); // 测试获取阶段2是父阶段的总进度
r($programplan->getTotalPercentTest($stageIDList[1]))        && p() && e('47'); // 测试获取阶段1的总进度
r($programplan->getTotalPercentTest($stageIDList[2], true))  && p() && e('0');  // 测试获取阶段5是父阶段的总进度
r($programplan->getTotalPercentTest($stageIDList[3]))        && p() && e('24'); // 测试获取阶段3的总进度
