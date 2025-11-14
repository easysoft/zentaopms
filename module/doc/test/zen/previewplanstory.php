#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewPlanStory();
timeout=0
cid=16201

- 步骤1:在setting视图下预览计划关联的需求 @3
- 步骤2:在list视图下根据ID列表预览需求 @3
- 步骤3:空idList的list视图 @0
- 步骤4:不存在的计划ID @0
- 步骤5:计划ID为0 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->product->range('1');
$storyTable->title->range('1-10')->prefix('需求标题');
$storyTable->type->range('story');
$storyTable->status->range('active{5},reviewing{3},closed{2}');
$storyTable->stage->range('wait{3},planned{3},developing{2},released{2}');
$storyTable->version->range('1');
$storyTable->deleted->range('0');
$storyTable->gen(10);

zenData('storyspec')->gen(10);

$planStoryTable = zenData('planstory');
$planStoryTable->plan->range('1{3},2{3},0{4}');
$planStoryTable->story->range('1-10');
$planStoryTable->order->range('1-10');
$planStoryTable->gen(10);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsPlan1 = array('action' => 'preview', 'plan' => 1);
$settingsPlan999 = array('action' => 'preview', 'plan' => 999);
$settingsPlan0 = array('action' => 'preview', 'plan' => 0);
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewPlanStoryTest('setting', $settingsPlan1, '')['data'])) && p() && e('3'); // 步骤1:在setting视图下预览计划关联的需求
r(count($docTest->previewPlanStoryTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤2:在list视图下根据ID列表预览需求
r(count($docTest->previewPlanStoryTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤3:空idList的list视图
r(count($docTest->previewPlanStoryTest('setting', $settingsPlan999, '')['data'])) && p() && e('0'); // 步骤4:不存在的计划ID
r(count($docTest->previewPlanStoryTest('setting', $settingsPlan0, '')['data'])) && p() && e('4'); // 步骤5:计划ID为0