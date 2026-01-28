#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

function initData()
{
    $casedata = zenData('case');
    $casedata->id->range('1-10');

    $casefiledata = zenData('file');
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
cid=19042

- 测试用例1的附件数 @3
- 测试用例1的附件数
 - 第1条的id属性 @1
 - 第1条的title属性 @文件标题1
 - 第1条的extension属性 @txt
- 测试用例2的附件数 @7

*/

$cases = array('1', '2');

global $tester;
$tester->loadModel('testcase');

r(count($tester->testcase->getRelatedFiles($cases)[1])) && p('') && e('3'); // 测试用例1的附件数
r($tester->testcase->getRelatedFiles($cases)[1])        && p('1:id,title,extension') && e('1,文件标题1,txt'); // 测试用例1的附件数
r(count($tester->testcase->getRelatedFiles($cases)[2])) && p('') && e('7'); // 测试用例2的附件数