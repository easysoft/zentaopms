#!/usr/bin/env php
<?php

/**

title=测试 programplanTao::buildPointDataForGantt();
timeout=0
cid=17764

- 步骤1：正常评审点数据构建属性id @1-pointPP-1
- 步骤2：验证数据类型属性type @point
- 步骤3：验证评审状态属性rawStatus @pass
- 步骤4：DCP类别评审点处理属性id @2-pointDCP-2
- 步骤5：TR类别评审点处理属性id @3-pointTR4-3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

// 1. 准备测试数据
zendata('object')->loadYaml('object_buildpointdataforgantt', false, 2)->gen(5);
zendata('review')->loadYaml('review_buildpointdataforgantt', false, 2)->gen(5);

$project = zenData('project');
$project->type->range('stage');
$project->attribute->range('devel');
$project->begin->range('`2023-09-01`');
$project->end->range('`2023-12-31`');
$project->gen(3);

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$programplanTest = new programplanTest();

// 4. 构建测试评审点对象
$normalPoint = new stdclass();
$normalPoint->id = 1;
$normalPoint->category = 'PP';
$normalPoint->title = '评审点标题1';
$normalPoint->status = 'pass';
$normalPoint->reviewID = 1;
$normalPoint->lastReviewedDate = '2023-06-01 14:00:00';
$normalPoint->createdDate = '2023-01-01 09:00:00';
$normalPoint->end = '2023-12-31';

$pointWithoutEnd = new stdclass();
$pointWithoutEnd->id = 2;
$pointWithoutEnd->category = 'DCP';
$pointWithoutEnd->title = '基线评审点2';
$pointWithoutEnd->status = 'reviewing';
$pointWithoutEnd->reviewID = 2;
$pointWithoutEnd->lastReviewedDate = '';
$pointWithoutEnd->createdDate = '2023-02-01 10:00:00';
$pointWithoutEnd->end = '';

$trPoint = new stdclass();
$trPoint->id = 3;
$trPoint->category = 'TR4';
$trPoint->title = '测试评审点3';
$trPoint->status = 'wait';
$trPoint->reviewID = 3;
$trPoint->lastReviewedDate = '';
$trPoint->createdDate = '2023-03-01 11:00:00';
$trPoint->end = '';

$dcpPoint = new stdclass();
$dcpPoint->id = 4;
$dcpPoint->category = 'PDCP';
$dcpPoint->title = '设计评审点4';
$dcpPoint->status = 'fail';
$dcpPoint->reviewID = 4;
$dcpPoint->lastReviewedDate = '2023-07-01 15:00:00';
$dcpPoint->createdDate = '2023-01-15 09:30:00';
$dcpPoint->end = '';

// 5. 准备reviewDeadline数据
$reviewDeadline = array(
    1 => array('stageEnd' => '2023-12-31', 'stageBegin' => '2023-01-01'),
    2 => array('stageEnd' => '2024-01-31', 'stageBegin' => '2023-02-01'),
    3 => array('stageEnd' => '2024-02-28', 'stageBegin' => '2023-03-01', 'taskEnd' => '2023-11-30')
);

// 6. 执行测试步骤
r($programplanTest->buildPointDataForGanttTest(1, $normalPoint, $reviewDeadline)) && p('id') && e('1-pointPP-1'); // 步骤1：正常评审点数据构建
r($programplanTest->buildPointDataForGanttTest(1, $normalPoint, $reviewDeadline)) && p('type') && e('point'); // 步骤2：验证数据类型
r($programplanTest->buildPointDataForGanttTest(1, $normalPoint, $reviewDeadline)) && p('rawStatus') && e('pass'); // 步骤3：验证评审状态
r($programplanTest->buildPointDataForGanttTest(2, $pointWithoutEnd, $reviewDeadline)) && p('id') && e('2-pointDCP-2'); // 步骤4：DCP类别评审点处理
r($programplanTest->buildPointDataForGanttTest(3, $trPoint, $reviewDeadline)) && p('id') && e('3-pointTR4-3'); // 步骤5：TR类别评审点处理
