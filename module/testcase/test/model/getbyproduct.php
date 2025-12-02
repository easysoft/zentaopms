#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

function initData()
{
    $caseData = zenData('case');
    $caseData->product->range('1{6},2{4}');

    $caseData->gen(10);
}

initData();

/**

title=测试 testcaseModel->getBySuite();
timeout=0
cid=18979

- 产品1的用例有6个 @6
- 产品2的用例有4个 @4
- 产品1的用例详情
 - 第1条的id属性 @1
 - 第1条的project属性 @0
 - 第1条的title属性 @这个是测试用例1
- 产品2的用例详情
 - 第7条的id属性 @7
 - 第7条的project属性 @0
 - 第7条的title属性 @这个是测试用例7

*/

$productIDList = array(1, 2);
$branch        = 0;
$suiteIDList   = array('1', '2', '3', '4');

global $tester;
$tester->loadModel('testcase');

r(count($tester->testcase->getByProduct($productIDList[0]))) && p() && e(6); // 产品1的用例有6个
r(count($tester->testcase->getByProduct($productIDList[1]))) && p() && e(4); // 产品2的用例有4个
r($tester->testcase->getByProduct($productIDList[0]))        && p('1:id,project,title') && e('1,0,这个是测试用例1'); // 产品1的用例详情
r($tester->testcase->getByProduct($productIDList[1]))        && p('7:id,project,title') && e('7,0,这个是测试用例7'); // 产品2的用例详情