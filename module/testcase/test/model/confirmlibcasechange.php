#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('module')->gen(10);
zenData('branch')->gen(0);
zenData('story')->gen(10);

$case = zenData('case');
$case->id->range('1-3');
$case->product->range('1{3}');
$case->title->range('用例A,用例库B,用例C');
$case->precondition->range('前置A,前置库B,前置C');
$case->keywords->range('关键词A,关键词库B,关键词C');
$case->version->range('3,3,1');
$case->fromCaseVersion->range('1,3,1');
$case->lib->range('0,1,0');
$case->type->range('feature{3}');
$case->status->range('normal{3}');
$case->deleted->range('0{3}');
$case->gen(3);

$step = zenData('casestep');
$step->id->range('1-5');
$step->case->range('2{5}');
$step->version->range('3{5}');
$step->type->range('step{5}');
$step->desc->range('步骤描述1,步骤描述2,步骤描述3,步骤描述4,步骤描述5');
$step->expect->range('预期结果1,预期结果2,预期结果3,预期结果4,预期结果5');
$step->gen(5);

zenData('file')->gen(0);

/**

title=测试 testcaseModel->confirmLibCaseChange();
cid=0

- 正常确认用例库变更：验证用例version变为4 @4
- 正常确认用例库变更：验证用例fromCaseVersion变为4 @4
- 正常确认用例库变更：验证用例title更新为用例库标题 @用例库B
- 正常确认用例库变更：验证用例precondition更新为用例库前置条件 @前置库B
- 正常确认用例库变更：验证用例keywords更新为用例库关键词 @关键词库B
- 正常确认用例库变更：验证用例步骤数量 @5
- 正常确认用例库变更：验证第一条步骤的desc字段 @步骤描述1
- 正常确认用例库变更：验证第一条步骤的expect字段 @预期结果1
- 正常确认用例库变更：验证第一步步骤的version字段 @4

*/
$testcase = new testcaseModelTest();

$result = $testcase->confirmLibCaseChangeTest(1, 2);
r($result) && p('case:version')          && e('4');         // 正常确认用例库变更：验证用例version变为4
r($result) && p('case:fromCaseVersion')  && e('4');         // 正常确认用例库变更：验证用例fromCaseVersion变为4
r($result) && p('case:title')            && e('用例库B');   // 正常确认用例库变更：验证用例title更新为用例库标题
r($result) && p('case:precondition')     && e('前置库B');   // 正常确认用例库变更：验证用例precondition更新为用例库前置条件
r($result) && p('case:keywords')         && e('关键词库B'); // 正常确认用例库变更：验证用例keywords更新为用例库关键词
r($result) && p('stepCount')             && e('5');         // 正常确认用例库变更：验证用例步骤数量
r($result) && p('0:desc')                && e('步骤描述1'); // 正常确认用例库变更：验证第一条步骤的desc字段
r($result) && p('0:expect')              && e('预期结果1'); // 正常确认用例库变更：验证第一条步骤的expect字段
r($result) && p('0:version')             && e('4');         // 正常确认用例库变更：验证第一步步骤的version字段
