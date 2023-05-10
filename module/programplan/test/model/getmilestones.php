#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

function initData()
{
     zdTable('project')->config('project')->gen(5);
     zdTable('project')->config('stage')->gen(5, $isClear = false);
}

/**

title=测试 programplanModel->getMilestones();
cid=1
pid=1

*/

initData();

$programplan = new programplanTest();
r($programplan->getMilestonesTest($projectID = 1)) && p() && e('阶段-测试/阶段-发布,阶段-开发/阶段-测试,阶段-设计/阶段-开发,阶段-需求/阶段-设计,阶段-需求'); // 测试获取项目ID为1的里程碑
r($programplan->getMilestonesTest($projectID = 2)) && p() && e('0'); // 测试获取项目id为2的里程碑信息