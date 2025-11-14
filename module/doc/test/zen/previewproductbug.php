#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductBug();
timeout=0
cid=16202

- 步骤1:在setting视图下预览产品1的Bug @5
- 步骤2:在list视图下根据ID列表预览Bug @3
- 步骤3:空idList的list视图 @10
- 步骤4:不存在的产品ID @0
- 步骤5:产品ID为0的情况 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->product->range('1{5},2{3},0{2}');
$bugTable->execution->range('0');
$bugTable->plan->range('0');
$bugTable->title->range('1-10')->prefix('Bug标题');
$bugTable->status->range('active{5},resolved{3},closed{2}');
$bugTable->deleted->range('0');
$bugTable->gen(10);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsProduct1 = array('action' => 'preview', 'product' => 1, 'condition' => 'all');
$settingsProduct999 = array('action' => 'preview', 'product' => 999, 'condition' => 'all');
$settingsProduct0 = array('action' => 'preview', 'product' => 0, 'condition' => 'all');
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewProductBugTest('setting', $settingsProduct1, '')['data'])) && p() && e('5'); // 步骤1:在setting视图下预览产品1的Bug
r(count($docTest->previewProductBugTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤2:在list视图下根据ID列表预览Bug
r(count($docTest->previewProductBugTest('list', $settingsList, '')['data'])) && p() && e('10'); // 步骤3:空idList的list视图
r(count($docTest->previewProductBugTest('setting', $settingsProduct999, '')['data'])) && p() && e('0'); // 步骤4:不存在的产品ID
r(count($docTest->previewProductBugTest('setting', $settingsProduct0, '')['data'])) && p() && e('2'); // 步骤5:产品ID为0的情况