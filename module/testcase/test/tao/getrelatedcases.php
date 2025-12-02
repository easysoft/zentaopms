#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

function initData()
{
    $casedata = zenData('case');
    $casedata->id->range('1-10');
    $casedata->linkCase->range('2,3{1}0{9}');

    $casedata->gen(10);
}

initData();

/**

title=测试 testcaseModel->getRelatedCases();
timeout=0
cid=19041

- 测试获取关联的用例
 - 属性1 @这个是测试用例1
 - 属性2 @这个是测试用例2
 - 属性3 @这个是测试用例3
 - 属性4 @这个是测试用例4
 - 属性5 @这个是测试用例5

*/

$testcase = new testcaseTest();
r($testcase->getRelatedCasesTest(array('1', '2', '3', '4', '5'))) && p('1;2;3;4;5') && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4;这个是测试用例5'); // 测试获取关联的用例