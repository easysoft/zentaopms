#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

function initData()
{
    $caseData = zenData('case');
    $caseData->product->range('1{5},2{5}');

    $caseSuiteData = zenData('suitecase');
    $caseSuiteData->product->range('1{5},2{5}');
    $caseSuiteData->suite->range('1-4');

    $caseData->gen(10);
    $caseSuiteData->gen(10);
}

initData();

/**

title=测试 testcaseModel->getBySuite();
timeout=0
cid=19037

- 测试获取产品 1 套件 1 的case
 - 第1条的title属性 @这个是测试用例1
 - 第5条的title属性 @这个是测试用例5
- 测试获取产品 1 套件 2 的case第2条的title属性 @这个是测试用例2
- 测试获取产品 1 套件 3 的case第3条的title属性 @这个是测试用例3
- 测试获取产品 1 套件 4 的case第4条的title属性 @这个是测试用例4
- 测试获取产品 2 套件 1 的case第9条的title属性 @这个是测试用例9
- 测试获取产品 2 套件 2 的case
 - 第6条的title属性 @这个是测试用例6
 - 第10条的title属性 @这个是测试用例10
- 测试获取产品 2 套件 3 的case第7条的title属性 @这个是测试用例7
- 测试获取产品 2 套件 4 的case第8条的title属性 @这个是测试用例8

*/
$productIDList = array(1, 2);
$branch        = 0;
$suiteIDList   = array('1', '2', '3', '4');

$testcase = new testcaseTaoTest();

r($testcase->getBySuiteTest($productIDList[0], $branch, $suiteIDList[0])) && p('1:title;5:title')  && e('这个是测试用例1;这个是测试用例5');  // 测试获取产品 1 套件 1 的case
r($testcase->getBySuiteTest($productIDList[0], $branch, $suiteIDList[1])) && p('2:title')          && e('这个是测试用例2');                  // 测试获取产品 1 套件 2 的case
r($testcase->getBySuiteTest($productIDList[0], $branch, $suiteIDList[2])) && p('3:title')          && e('这个是测试用例3');                  // 测试获取产品 1 套件 3 的case
r($testcase->getBySuiteTest($productIDList[0], $branch, $suiteIDList[3])) && p('4:title')          && e('这个是测试用例4');                  // 测试获取产品 1 套件 4 的case
r($testcase->getBySuiteTest($productIDList[1], $branch, $suiteIDList[0])) && p('9:title')          && e('这个是测试用例9');                  // 测试获取产品 2 套件 1 的case
r($testcase->getBySuiteTest($productIDList[1], $branch, $suiteIDList[1])) && p('6:title;10:title') && e('这个是测试用例6;这个是测试用例10'); // 测试获取产品 2 套件 2 的case
r($testcase->getBySuiteTest($productIDList[1], $branch, $suiteIDList[2])) && p('7:title')          && e('这个是测试用例7');                  // 测试获取产品 2 套件 3 的case
r($testcase->getBySuiteTest($productIDList[1], $branch, $suiteIDList[3])) && p('8:title')          && e('这个是测试用例8');                  // 测试获取产品 2 套件 4 的case