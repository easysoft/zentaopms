#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';

su('admin');

/**

title=测试 caselibTao->insertImportedCase();
timeout=0
cid=1

- 执行caselib模块的insertImportedCaseTest方法，参数是0, $caseData, $data, $forceNotReview[0] 
 - 属性title @用例1
 - 属性type @feature
 - 属性status @normal
- 执行caselib模块的insertImportedCaseTest方法，参数是0, $caseData, $data, $forceNotReview[1] 
 - 属性title @用例1
 - 属性type @feature
 - 属性status @wait

*/

$data = new stdclass();
$data->desc[][1]     = '步骤一';
$data->expect[][1]   = '预期一';
$data->stepType[][1] = 'item';

$caseData = new stdclass();
$caseData->title = '用例1';
$caseData->type  = 'feature';

$forceNotReview = array(true, false);

$caselib = new caselibTest();
r($caselib->insertImportedCaseTest(0, $caseData, $data, $forceNotReview[0])) && p('title;type;status') && e('用例1;feature;normal');
r($caselib->insertImportedCaseTest(0, $caseData, $data, $forceNotReview[1])) && p('title;type;status') && e('用例1;feature;wait');