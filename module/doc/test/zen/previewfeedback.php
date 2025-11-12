#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewFeedback();
timeout=0
cid=0

- 步骤1:customSearch条件预览反馈列表,status=wait @3
- 步骤2:customSearch条件预览反馈列表,pri=1 @2
- 步骤3:customSearch条件预览反馈列表,status=doing @2
- 步骤4:customSearch条件预览反馈列表,type=bug @3
- 步骤5:不存在的product @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$feedbackTable = zenData('feedback');
$feedbackTable->product->range('1{8},2{2}');
$feedbackTable->title->range('1-10')->prefix('反馈标题');
$feedbackTable->status->range('wait{3},doing{2},done{3},closed{2}');
$feedbackTable->pri->range('1{2},2{4},3{4}');
$feedbackTable->type->range('bug{3},feature{3},improvement{2},question{2}');
$feedbackTable->openedBy->range('admin');
$feedbackTable->gen(10, false);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsCustomSearch1 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('wait'), 'andor' => array('and'));
$settingsCustomSearch2 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('pri'), 'operator' => array('='), 'value' => array('1'), 'andor' => array('and'));
$settingsCustomSearch3 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('doing'), 'andor' => array('and'));
$settingsCustomSearch4 = array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('type'), 'operator' => array('='), 'value' => array('bug'), 'andor' => array('and'));
$settingsNoProduct = array('action' => 'preview', 'product' => 999, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('wait'), 'andor' => array('and'));

r(count($docTest->previewFeedbackTest('setting', $settingsCustomSearch1, '')['data'])) && p() && e('3'); // 步骤1:customSearch条件预览反馈列表,status=wait
r(count($docTest->previewFeedbackTest('setting', $settingsCustomSearch2, '')['data'])) && p() && e('2'); // 步骤2:customSearch条件预览反馈列表,pri=1
r(count($docTest->previewFeedbackTest('setting', $settingsCustomSearch3, '')['data'])) && p() && e('2'); // 步骤3:customSearch条件预览反馈列表,status=doing
r(count($docTest->previewFeedbackTest('setting', $settingsCustomSearch4, '')['data'])) && p() && e('3'); // 步骤4:customSearch条件预览反馈列表,type=bug
r(count($docTest->previewFeedbackTest('setting', $settingsNoProduct, '')['data'])) && p() && e('0'); // 步骤5:不存在的product