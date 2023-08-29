#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

function initData()
{
    $bugdata = zdTable('bug');
    $bugdata->id->range('1-10');
    $bugdata->product->range('1');
    $bugdata->branch->range('1-2');
    $bugdata->case->range('3,0{9}');

    $bugdata->gen(10);
}

initData();

/**

title=测试 testcaseModel->getCases2Link();
timeout=0
cid=1

- 获取相关的 bug @4

*/

$caseIDList     = array('1');
$browseTypeList = array('bySearch');

$testcase = new testcaseTest();

r(count($testcase->getBugs2LinkTest($caseIDList[0], $browseTypeList[0]))) && p('') && e('4'); // 获取相关的 bug