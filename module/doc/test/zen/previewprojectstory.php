#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProjectStory();
timeout=0
cid=16206

- 步骤1:setting视图下customSearch条件预览项目1的需求(status=active) @5
- 步骤2:setting视图下customSearch条件预览项目1的需求(status=closed) @3
- 步骤3:setting视图下all条件预览项目1的所有需求 @8
- 步骤4:list视图下根据ID列表预览需求 @3
- 步骤5:空idList的list视图 @0
- 步骤6:不存在的项目ID预览需求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->name->range('1-3')->prefix('项目');
$projectTable->type->range('project');
$projectTable->status->range('doing');
$projectTable->deleted->range('0');
$projectTable->gen(3);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->vision->range('rnd');
$storyTable->product->range('1');
$storyTable->type->range('story');
$storyTable->title->range('1-10')->prefix('研发需求');
$storyTable->status->range('active{5},closed{3},draft{2}');
$storyTable->version->range('1');
$storyTable->deleted->range('0');
$storyTable->gen(10);

$projectStoryTable = zenData('projectstory');
$projectStoryTable->project->range('1{8},2{2}');
$projectStoryTable->product->range('1');
$projectStoryTable->story->range('1-10');
$projectStoryTable->version->range('1');
$projectStoryTable->gen(10);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-10');
$storySpecTable->version->range('1');
$storySpecTable->title->range('1-10')->prefix('需求标题');
$storySpecTable->spec->range('1-10')->prefix('需求描述');
$storySpecTable->gen(10);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsActiveStatus = array('action' => 'preview', 'project' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsClosedStatus = array('action' => 'preview', 'project' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('closed'), 'andor' => array('and'));
$settingsAllCondition = array('action' => 'preview', 'project' => 1, 'condition' => 'all');
$settingsNoProject = array('action' => 'preview', 'project' => 999, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewProjectStoryTest('setting', $settingsActiveStatus, '')['data'])) && p() && e('5'); // 步骤1:setting视图下customSearch条件预览项目1的需求(status=active)
r(count($docTest->previewProjectStoryTest('setting', $settingsClosedStatus, '')['data'])) && p() && e('3'); // 步骤2:setting视图下customSearch条件预览项目1的需求(status=closed)
r(count($docTest->previewProjectStoryTest('setting', $settingsAllCondition, '')['data'])) && p() && e('8'); // 步骤3:setting视图下all条件预览项目1的所有需求
r(count($docTest->previewProjectStoryTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤4:list视图下根据ID列表预览需求
r(count($docTest->previewProjectStoryTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤5:空idList的list视图
r(count($docTest->previewProjectStoryTest('setting', $settingsNoProject, '')['data'])) && p() && e('0'); // 步骤6:不存在的项目ID预览需求