#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewExecutionStory();
timeout=0
cid=0

- 步骤1:setting视图下根据execution ID预览执行需求列表,all条件 @5
- 步骤2:setting视图下根据execution ID预览执行需求列表,unclosed条件 @5
- 步骤3:list视图下根据ID列表预览执行需求 @3
- 步骤4:空idList的list视图预览执行需求 @0
- 步骤5:没有关联需求的execution预览执行需求列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$storyTable = zenData('story');
$storyTable->id->range('1-20');
$storyTable->product->range('1{10},2{10}');
$storyTable->type->range('story');
$storyTable->title->range('1-20')->prefix('执行需求标题');
$storyTable->status->range('active{2},draft{2},closed{1},active{1},draft{3},closed{4},changing{7}');
$storyTable->stage->range('wait');
$storyTable->pri->range('1{2},2{8},3{8},4{2}');
$storyTable->version->range('1');
$storyTable->parent->range('0');
$storyTable->isParent->range('0');
$storyTable->deleted->range('0');
$storyTable->gen(20);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-20');
$storySpecTable->version->range('1');
$storySpecTable->title->range('1-20')->prefix('执行需求标题');
$storySpecTable->gen(20);

$projectStoryTable = zenData('projectstory');
$projectStoryTable->project->range('1{5},2{10},3{5}');
$projectStoryTable->product->range('1{5},2{5},1{5},2{5}');
$projectStoryTable->story->range('1-20');
$projectStoryTable->version->range('1');
$projectStoryTable->gen(20);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('1-5')->prefix('产品');
$productTable->type->range('normal');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('1-5')->prefix('执行');
$projectTable->type->range('sprint{3},stage{2}');
$projectTable->status->range('wait');
$projectTable->deleted->range('0');
$projectTable->gen(5);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsAll = array('action' => 'preview', 'execution' => 1, 'condition' => 'all');
$settingsUnclosed = array('action' => 'preview', 'execution' => 1, 'condition' => 'unclosed');
$settingsNoStory = array('action' => 'preview', 'execution' => 5, 'condition' => 'all');
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewExecutionStoryTest('setting', $settingsAll, '')['data'])) && p() && e('5'); // 步骤1:setting视图下根据execution ID预览执行需求列表,all条件
r(count($docTest->previewExecutionStoryTest('setting', $settingsUnclosed, '')['data'])) && p() && e('5'); // 步骤2:setting视图下根据execution ID预览执行需求列表,unclosed条件
r(count($docTest->previewExecutionStoryTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤3:list视图下根据ID列表预览执行需求
r(count($docTest->previewExecutionStoryTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤4:空idList的list视图预览执行需求
r(count($docTest->previewExecutionStoryTest('setting', $settingsNoStory, '')['data'])) && p() && e('0'); // 步骤5:没有关联需求的execution预览执行需求列表