#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

function initData()
{
    $caseData = zenData('case');
    $caseData->product->range('1-2');
    $caseData->module->range('1{5},2{5}');

    $caseData->gen(10);

    $projectCase = zenData('projectcase');
    $projectCase->project->range('1');
    $projectCase->product->range('1-2');
    $projectCase->case->range('1-10');

    $projectCase->gen(10);
}

initData();

/**

title=测试 testcaseModel->getModuleProjectCases();
timeout=0
cid=19039

- 测试获取产品1的case
 - 第1条的title属性 @这个是测试用例1
 - 第3条的title属性 @这个是测试用例3
 - 第5条的title属性 @这个是测试用例5
 - 第7条的title属性 @这个是测试用例7
 - 第9条的title属性 @这个是测试用例9
- 测试获取产品1，模块1的case
 - 第1条的title属性 @这个是测试用例1
 - 第3条的title属性 @这个是测试用例3
 - 第5条的title属性 @这个是测试用例5
- 测试获取产品2的case
 - 第2条的title属性 @这个是测试用例2
 - 第4条的title属性 @这个是测试用例4
 - 第6条的title属性 @这个是测试用例6
 - 第8条的title属性 @这个是测试用例8
 - 第10条的title属性 @这个是测试用例10
- 测试获取产品2，模块2的case
 - 第6条的title属性 @这个是测试用例6
 - 第8条的title属性 @这个是测试用例8
 - 第10条的title属性 @这个是测试用例10

*/

$productIDList = array(1, 2);
$branch        = 0;
$moduleIDList  = array(array(1), array(2));

$testcase = new testcaseTest();

r($testcase->getModuleProjectCasesTest($productIDList[0]))                            && p('1:title;3:title;5:title;7:title;9:title')  && e('这个是测试用例1;这个是测试用例3;这个是测试用例5;这个是测试用例7;这个是测试用例9');  // 测试获取产品1的case
r($testcase->getModuleProjectCasesTest($productIDList[0], $branch, $moduleIDList[0])) && p('1:title;3:title;5:title')                  && e('这个是测试用例1;这个是测试用例3;这个是测试用例5');                                  // 测试获取产品1，模块1的case
r($testcase->getModuleProjectCasesTest($productIDList[1]))                            && p('2:title;4:title;6:title;8:title;10:title') && e('这个是测试用例2;这个是测试用例4;这个是测试用例6;这个是测试用例8;这个是测试用例10'); // 测试获取产品2的case
r($testcase->getModuleProjectCasesTest($productIDList[1], $branch, $moduleIDList[1])) && p('6:title;8:title;10:title')                 && e('这个是测试用例6;这个是测试用例8;这个是测试用例10');                                  // 测试获取产品2，模块2的case