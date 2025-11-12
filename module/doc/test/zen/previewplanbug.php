#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewPlanBug();
timeout=0
cid=0

- 步骤1:在setting视图下预览计划关联的Bug @3
- 步骤2:在list视图下根据ID列表预览Bug @3
- 步骤3:空idList的list视图 @10
- 步骤4:不存在的计划ID @0
- 步骤5:计划ID为0 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->product->range('1');
$bugTable->execution->range('0');
$bugTable->plan->range('1{3},2{3},0{4}');
$bugTable->title->range('1-10')->prefix('Bug标题');
$bugTable->status->range('active{5},resolved{3},closed{2}');
$bugTable->deleted->range('0');
$bugTable->gen(10);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsPlan1 = array('action' => 'preview', 'plan' => 1);
$settingsPlan999 = array('action' => 'preview', 'plan' => 999);
$settingsPlan0 = array('action' => 'preview', 'plan' => 0);
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewPlanBugTest('setting', $settingsPlan1, '')['data'])) && p() && e('3'); // 步骤1:在setting视图下预览计划关联的Bug
r(count($docTest->previewPlanBugTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤2:在list视图下根据ID列表预览Bug
r(count($docTest->previewPlanBugTest('list', $settingsList, '')['data'])) && p() && e('10'); // 步骤3:空idList的list视图
r(count($docTest->previewPlanBugTest('setting', $settingsPlan999, '')['data'])) && p() && e('0'); // 步骤4:不存在的计划ID
r(count($docTest->previewPlanBugTest('setting', $settingsPlan0, '')['data'])) && p() && e('4'); // 步骤5:计划ID为0