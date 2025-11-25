#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewCaselib();
timeout=0
cid=16196

- 步骤1:customSearch条件预览用例列表,pri=1 @3
- 步骤2:customSearch条件预览用例列表,pri=2 @2
- 步骤3:list视图下根据ID列表预览用例 @3
- 步骤4:空idList的list视图 @0
- 步骤5:不存在的caselib @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$caseTable = zenData('case');
$caseTable->lib->range('1{5},2{5}');
$caseTable->pri->range('1{3},2{4},3{3}');
$caseTable->title->range('1-10')->prefix('用例标题');
$caseTable->deleted->range('0');
$caseTable->gen(10);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsCustomSearch1 = array('action' => 'preview', 'caselib' => 1, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('1'), 'andor' => array('and'));
$settingsCustomSearch2 = array('action' => 'preview', 'caselib' => 1, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('2'), 'andor' => array('and'));
$settingsNoLib = array('action' => 'preview', 'caselib' => 999, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('1'), 'andor' => array('and'));
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewCaselibTest('setting', $settingsCustomSearch1, '')['data'])) && p() && e('3'); // 步骤1:customSearch条件预览用例列表,pri=1
r(count($docTest->previewCaselibTest('setting', $settingsCustomSearch2, '')['data'])) && p() && e('2'); // 步骤2:customSearch条件预览用例列表,pri=2
r(count($docTest->previewCaselibTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤3:list视图下根据ID列表预览用例
r(count($docTest->previewCaselibTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤4:空idList的list视图
r(count($docTest->previewCaselibTest('setting', $settingsNoLib, '')['data'])) && p() && e('0'); // 步骤5:不存在的caselib