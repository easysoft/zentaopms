#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

function initData()
{
    $caseData = zdTable('case');
    $caseData->product->range('1{6},2{4}');

    $caseData->gen(10);
}

initData();

/**

title=测试 testcaseModel->getBySuite();
timeout=0
cid=1

- 产品1用例有6个 @6
- 产品2的用例有4个 @4

*/

$productIDList = array(1, 2);
$branch        = 0;
$suiteIDList   = array('1', '2', '3', '4');

global $tester;
$tester->loadModel('testcase');

r(count($tester->testcase->getByProduct($productIDList[0]))) && p() && e(6); // 产品1的用例有6个
r(count($tester->testcase->getByProduct($productIDList[1]))) && p() && e(4); // 产品2的用例有4个
