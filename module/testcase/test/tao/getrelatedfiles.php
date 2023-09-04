#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

function initData()
{
    $casedata = zdTable('case');
    $casedata->id->range('1-10');

    $casefiledata = zdTable('file');
    $casefiledata->id->range('1-10');
    $casefiledata->objectType->range('testcase');
    $casefiledata->objectID->range('1{3},2{7}');

    $casedata->gen(10);
    $casefiledata->gen(10);
}

initData();

/**

title=测试 testcaseModel->getRelatedSteps();
timeout=0
cid=1

- 测试用例1的附件数 @3
- 测试用例2的附件数 @7

*/

$cases = array('1', '2');

global $tester;
$tester->loadModel('testcase');

r(count($tester->testcase->getRelatedFiles($cases)[1])) && p('') && e('3'); // 测试用例1的附件数
r(count($tester->testcase->getRelatedFiles($cases)[2])) && p('') && e('7'); // 测试用例2的附件数