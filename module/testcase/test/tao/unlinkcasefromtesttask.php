#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('testrun')->gen(10);

/**

title=测试 testcaseTao->unlinkCaseFromTesttask();
timeout=0
cid=19053

- 测试取消测试单与用例的关联。 @0
- 测试取消测试单与用例的关联。 @1
- 测试取消测试单与用例的关联。 @1
- 测试取消测试单与用例的关联。 @1
- 测试取消测试单与用例的关联。 @0

*/

$testcase = new testcaseTaoTest();
r($testcase->unlinkCaseFromTesttaskTest(1, 1)) && p() && e(0); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(0, 1)) && p() && e(1); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(1, 0)) && p() && e(1); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(0, 0)) && p() && e(1); //测试取消测试单与用例的关联。
r($testcase->unlinkCaseFromTesttaskTest(2, 2)) && p() && e(0); //测试取消测试单与用例的关联。