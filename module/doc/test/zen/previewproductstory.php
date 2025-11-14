#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductStory();
timeout=0
cid=16205

- 步骤1:setting视图下customSearch条件预览产品1的story类型需求(status=active) @5
- 步骤2:setting视图下customSearch条件预览产品1的story类型需求(status=closed) @3
- 步骤3:list视图下根据ID列表预览需求 @3
- 步骤4:空idList的list视图 @0
- 步骤5:不存在的产品ID预览需求 @0

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
$storyTable->id->range('1-10');
$storyTable->vision->range('rnd');
$storyTable->product->range('1{8},2{2}');
$storyTable->type->range('story');
$storyTable->title->range('1-10')->prefix('研发需求');
$storyTable->status->range('active{5},closed{3},draft{2}');
$storyTable->version->range('1');
$storyTable->deleted->range('0');
$storyTable->gen(10);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsActiveStatus = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsClosedStatus = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('closed'), 'andor' => array('and'));
$settingsNoProduct = array('action' => 'preview', 'product' => 999, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsList = array('action' => 'list');
$idList = '1,2,3';

$storyTable = zenData('storyspec');
$storyTable->story->range('1-10');
$storyTable->version->range('1');
$storyTable->title->range('1-10')->prefix('需求标题');
$storyTable->spec->range('1-10')->prefix('需求描述');
$storyTable->gen(10);

r(count($docTest->previewProductStoryTest('setting', $settingsActiveStatus, '')['data'])) && p() && e('5'); // 步骤1:setting视图下customSearch条件预览产品1的story类型需求(status=active)
r(count($docTest->previewProductStoryTest('setting', $settingsClosedStatus, '')['data'])) && p() && e('3'); // 步骤2:setting视图下customSearch条件预览产品1的story类型需求(status=closed)
r(count($docTest->previewProductStoryTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤3:list视图下根据ID列表预览需求
r(count($docTest->previewProductStoryTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤4:空idList的list视图
r(count($docTest->previewProductStoryTest('setting', $settingsNoProduct, '')['data'])) && p() && e('0'); // 步骤5:不存在的产品ID预览需求