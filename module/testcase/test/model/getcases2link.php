#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

function initData()
{
    $casedata = zdTable('case');
    $casedata->id->range('1-10');
    $casedata->product->range('1');
    $casedata->branch->range('1-2');
    $casedata->linkCase->range('3');

    $casedata->gen(10);
}

initData();

/**

title=测试 testcaseModel->getCases2Link();
timeout=0
cid=1

- 获取相关的用例 @3

*/

$caseIDList     = array('1');
$browseTypeList = array('bySearch');

$testcase = new testcaseTest();

r(count($testcase->getCases2LinkTest($caseIDList[0], $browseTypeList[0]))) && p('') && e('3'); // 获取相关的用例