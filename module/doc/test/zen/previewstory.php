#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewStory();
timeout=0
cid=0

- 步骤1:setting视图下customSearch条件预览产品1的story类型需求(status=active) @5
- 步骤2:setting视图下customSearch条件预览产品1的epic类型需求 @2
- 步骤3:setting视图下customSearch条件预览产品1的requirement类型需求 @3
- 步骤4:list视图下根据ID列表预览story类型需求 @3
- 步骤5:空idList的list视图 @0
- 步骤6:不存在的产品ID预览需求 @0
- 步骤7:setting视图下customSearch条件预览产品1的closed状态需求 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('1-3')->prefix('产品');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->gen(3);

$storyTable = zenData('story');
$storyTable->id->range('1-15');
$storyTable->vision->range('rnd');
$storyTable->product->range('1{13},2{2}');
$storyTable->type->range('story{8},epic{2},requirement{5}');
$storyTable->title->range('1-15')->prefix('需求');
$storyTable->status->range('active{5},closed{3},draft{2},active{5}');
$storyTable->version->range('1');
$storyTable->deleted->range('0');
$storyTable->gen(15);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-15');
$storySpecTable->version->range('1');
$storySpecTable->title->range('1-15')->prefix('需求标题');
$storySpecTable->spec->range('1-15')->prefix('需求描述');
$storySpecTable->gen(15);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsStoryActive = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsEpic = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('type'), 'operator' => array('='), 'value' => array('epic'), 'andor' => array('and'));
$settingsRequirement = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('type'), 'operator' => array('='), 'value' => array('requirement'), 'andor' => array('and'));
$settingsClosed = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('closed'), 'andor' => array('and'));
$settingsNoProduct = array('action' => 'preview', 'product' => 999, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewStoryTest('story', 'setting', $settingsStoryActive, '')['data'])) && p() && e('5'); // 步骤1:setting视图下customSearch条件预览产品1的story类型需求(status=active)
r(count($docTest->previewStoryTest('epic', 'setting', $settingsEpic, '')['data'])) && p() && e('2'); // 步骤2:setting视图下customSearch条件预览产品1的epic类型需求
r(count($docTest->previewStoryTest('requirement', 'setting', $settingsRequirement, '')['data'])) && p() && e('3'); // 步骤3:setting视图下customSearch条件预览产品1的requirement类型需求
r(count($docTest->previewStoryTest('story', 'list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤4:list视图下根据ID列表预览story类型需求
r(count($docTest->previewStoryTest('story', 'list', $settingsList, '')['data'])) && p() && e('0'); // 步骤5:空idList的list视图
r(count($docTest->previewStoryTest('story', 'setting', $settingsNoProduct, '')['data'])) && p() && e('0'); // 步骤6:不存在的产品ID预览需求
r(count($docTest->previewStoryTest('story', 'setting', $settingsClosed, '')['data'])) && p() && e('3'); // 步骤7:setting视图下customSearch条件预览产品1的closed状态需求