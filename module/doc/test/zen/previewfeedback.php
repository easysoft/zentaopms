#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewFeedback();
timeout=0
cid=0

- 步骤1:setting视图,preview动作,customSearch条件 @1
- 步骤2:setting视图,preview动作,customSearch条件 @1
- 步骤3:list视图,有效ID列表 @1
- 步骤4:setting视图,非preview动作 @1
- 步骤5:list视图,空ID列表 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('feedback')->loadYaml('previewfeedback/feedback', false, 2)->gen(50);
zendata('product')->loadYaml('previewfeedback/product', false, 2)->gen(10);

su('admin');

$docTest = new docZenTest();

$result1 = $docTest->previewFeedbackTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('wait'), 'andor' => array('and')), '');
$result2 = $docTest->previewFeedbackTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('product'), 'operator' => array('='), 'value' => array('1'), 'andor' => array('and')), '');
$result3 = $docTest->previewFeedbackTest('list', array('action' => 'list'), '1,2,3');
$result4 = $docTest->previewFeedbackTest('setting', array('action' => 'other', 'product' => 1, 'condition' => 'all'), '');
$result5 = $docTest->previewFeedbackTest('list', array('action' => 'list'), '');

r(is_array($result1)) && p() && e('1'); // 步骤1:setting视图,preview动作,customSearch条件
r(is_array($result2)) && p() && e('1'); // 步骤2:setting视图,preview动作,customSearch条件
r(is_array($result3)) && p() && e('1'); // 步骤3:list视图,有效ID列表
r(is_array($result4)) && p() && e('1'); // 步骤4:setting视图,非preview动作
r(is_array($result5)) && p() && e('1'); // 步骤5:list视图,空ID列表