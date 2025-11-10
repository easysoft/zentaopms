#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewER();
timeout=0
cid=16195

- 步骤1:setting视图下customSearch条件预览业务需求列表,status=active @3
- 步骤2:setting视图下customSearch条件预览业务需求列表,pri=1 @2
- 步骤3:list视图下根据ID列表预览业务需求 @3
- 步骤4:空idList的list视图预览业务需求 @0
- 步骤5:不存在的product预览业务需求列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$storyTable = zenData('story');
$storyTable->id->range('1-20');
$storyTable->product->range('1{10},2{10}');
$storyTable->type->range('epic{15},story{3},requirement{2}');
$storyTable->title->range('1-20')->prefix('业务需求标题');
$storyTable->status->range('active{3},draft{5},closed{5},changing{7}');
$storyTable->pri->range('1{2},2{8},3{8},4{2}');
$storyTable->version->range('1');
$storyTable->deleted->range('0');
$storyTable->gen(20);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-20');
$storySpecTable->version->range('1');
$storySpecTable->title->range('1-20')->prefix('业务需求标题');
$storySpecTable->gen(20);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsCustomSearch1 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsCustomSearch2 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('1'), 'andor' => array('and'));
$settingsNoProduct = array('action' => 'preview', 'product' => 999, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('and'));
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewERTest('setting', $settingsCustomSearch1, '')['data'])) && p() && e('3'); // 步骤1:setting视图下customSearch条件预览业务需求列表,status=active
r(count($docTest->previewERTest('setting', $settingsCustomSearch2, '')['data'])) && p() && e('2'); // 步骤2:setting视图下customSearch条件预览业务需求列表,pri=1
r(count($docTest->previewERTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤3:list视图下根据ID列表预览业务需求
r(count($docTest->previewERTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤4:空idList的list视图预览业务需求
r(count($docTest->previewERTest('setting', $settingsNoProduct, '')['data'])) && p() && e('0'); // 步骤5:不存在的product预览业务需求列表
