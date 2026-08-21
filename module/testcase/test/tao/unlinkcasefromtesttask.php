#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

$build = zenData('build');
$build->id->range('1-2');
$build->branch->range('1,2');
$build->gen(2);

$testtask = zenData('testtask');
$testtask->id->range('1-2');
$testtask->build->range('1,2');
$testtask->gen(2);

$testrun = zenData('testrun');
$testrun->id->range('1-4');
$testrun->task->range('1,2,1,2');
$testrun->case->range('1,1,2,2');
$testrun->gen(4);

/**

title=测试 testcaseTao->unlinkCaseFromTesttask();
timeout=0
cid=19053

- 测试取消测试单与用例的关联。 @0
- 测试取消测试单与用例的关联。 @1
- 测试取消测试单与用例的关联。 @0
- 测试取消测试单与用例的关联。 @1
- 测试取消测试单与用例的关联。 @0

*/

$testcase = new testcaseTaoTest();
r($testcase->unlinkCaseFromTesttaskTest(1, 1)) && p() && e(0); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(0, 1)) && p() && e(1); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(1, 0)) && p() && e(0); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(0, 0)) && p() && e(1); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(2, 2)) && p() && e(0); //测试取消测试单与用例的关联。