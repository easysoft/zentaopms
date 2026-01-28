#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');
zenData('case')->gen(10);
zenData('casestep')->gen(10);
zenData('casespec')->gen(0);
zenData('story')->gen(10);

/**

title=测试 caselibTao->updateImportedCase();
timeout=0
cid=15541

- 更新用例库 0 用例标题 强制评审
 - 属性title @用例更新
 - 第steps[0]条的desc属性 @步骤一更新
- 更新用例库 0 用例标题 非强制评审
 - 属性title @用例更新
 - 第steps[0]条的desc属性 @步骤一更新
- 更新用例库 1 用例标题 强制评审
 - 属性title @用例更新
 - 第steps[0]条的desc属性 @步骤一更新
- 更新用例库 1 用例标题 非强制评审
 - 属性title @用例更新
 - 第steps[0]条的desc属性 @步骤一更新
- 更新用例库 2 用例标题 强制评审
 - 属性title @用例更新
 - 第steps[0]条的desc属性 @步骤一更新
- 更新用例库 2 用例标题 非强制评审
 - 属性title @用例更新
 - 第steps[0]条的desc属性 @步骤一更新

*/

$data = new stdclass();
$data->id = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10');

$caseData = new stdclass();
$caseData->title        = '用例更新';
$caseData->lib          = 0;
$caseData->precondition = '';
$caseData->steps        = array('1' => '步骤一更新');
$caseData->expects      = array('1' => '');
$caseData->stepType     = array('1' => 'step');
$caseData->version      = 1;

$case = clone $caseData;

$forceNotReview = array(true, false);

$caselib = new caselibTaoTest();
r($caselib->updateImportedCaseTest(0, $case, $data, $forceNotReview[0])) && p('title;steps[0]:desc') && e('用例更新;步骤一更新'); // 更新用例库 0 用例标题 强制评审
$case = clone $caseData;
r($caselib->updateImportedCaseTest(0, $case, $data, $forceNotReview[1])) && p('title;steps[0]:desc') && e('用例更新;步骤一更新'); // 更新用例库 0 用例标题 非强制评审
$case = clone $caseData;
r($caselib->updateImportedCaseTest(1, $case, $data, $forceNotReview[0])) && p('title;steps[0]:desc') && e('用例更新;步骤一更新'); // 更新用例库 1 用例标题 强制评审
$case = clone $caseData;
r($caselib->updateImportedCaseTest(1, $case, $data, $forceNotReview[1])) && p('title;steps[0]:desc') && e('用例更新;步骤一更新'); // 更新用例库 1 用例标题 非强制评审
$case = clone $caseData;
r($caselib->updateImportedCaseTest(2, $case, $data, $forceNotReview[0])) && p('title;steps[0]:desc') && e('用例更新;步骤一更新'); // 更新用例库 2 用例标题 强制评审
$case = clone $caseData;
r($caselib->updateImportedCaseTest(2, $case, $data, $forceNotReview[1])) && p('title;steps[0]:desc') && e('用例更新;步骤一更新'); // 更新用例库 2 用例标题 非强制评审
