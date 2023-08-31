#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

function initData()
{
    $casedata = zdTable('case');
    $casedata->id->range('1-10');
    $casedata->case->range('2,3{1}0{9}');

    $casedata->gen(10);
}

initData();

/**

title=测试 testcaseModel->getRelatedCases();
timeout=0
cid=1

- 测试获取关联的用例
 - 属性2 @这个是测试用例2
 - 属性3 @这个是测试用例3

*/

$linkCases = array('2,3');

$testcase = new testcaseTest();
r($testcase->getRelatedCasesTest($linkCases)) && p('2;3') && e('这个是测试用例2;这个是测试用例3'); // 测试获取关联的用例
