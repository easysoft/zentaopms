#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

function initData()
{
    $casedata = zenData('case');
    $casedata->id->range('1-10');

    $casestepdata = zenData('casestep');
    $casestepdata->id->range('1-10');
    $casestepdata->case->range('1{6},2{4}');

    $casedata->gen(10);
    $casestepdata->gen(10);
}

initData();

/**

title=测试 testcaseModel->getRelatedSteps();
timeout=0
cid=19043

- 测试用例1的步骤数 @6
- 测试用例1的步骤详情
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的version属性 @1
- 测试用例2的步骤数 @4

*/

$cases = array('1', '2');

global $tester;
$tester->loadModel('testcase');

r(count($tester->testcase->getRelatedSteps($cases)[1])) && p('') && e('6'); // 测试用例1的步骤数
r($tester->testcase->getRelatedSteps($cases)[1])        && p('1:id,case,version') && e('1,1,1'); // 测试用例1的步骤详情
r(count($tester->testcase->getRelatedSteps($cases)[2])) && p('') && e('4'); // 测试用例2的步骤数