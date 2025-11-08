#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductCase();
timeout=0
cid=0

- 步骤1:setting视图下customSearch条件预览产品用例列表,pri=1 @3
- 步骤2:setting视图下customSearch条件预览产品用例列表,pri=2 @2
- 步骤3:setting视图下普通条件预览产品用例列表 @5
- 步骤4:list视图下根据ID列表预览用例 @3
- 步骤5:空idList的list视图 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$caseTable = zenData('case');
$caseTable->product->range('1{5},2{5}');
$caseTable->pri->range('1{3},2{2},3{5}');
$caseTable->title->range('1-10')->prefix('测试用例');
$caseTable->type->range('feature{5},performance{3},config{2}');
$caseTable->status->range('normal{8},blocked{2}');
$caseTable->stage->range('unittest{3},feature{4},intergrate{3}');
$caseTable->deleted->range('0');
$caseTable->gen(10);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsCustomSearch1 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('1'), 'andor' => array('and'));
$settingsCustomSearch2 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('2'), 'andor' => array('and'));
$settingsNormalCondition = array('action' => 'preview', 'product' => 1, 'condition' => 'all');
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewProductCaseTest('setting', $settingsCustomSearch1, '')['data'])) && p() && e('3'); // 步骤1:setting视图下customSearch条件预览产品用例列表,pri=1
r(count($docTest->previewProductCaseTest('setting', $settingsCustomSearch2, '')['data'])) && p() && e('2'); // 步骤2:setting视图下customSearch条件预览产品用例列表,pri=2
r(count($docTest->previewProductCaseTest('setting', $settingsNormalCondition, '')['data'])) && p() && e('5'); // 步骤3:setting视图下普通条件预览产品用例列表
r(count($docTest->previewProductCaseTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤4:list视图下根据ID列表预览用例
r(count($docTest->previewProductCaseTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤5:空idList的list视图