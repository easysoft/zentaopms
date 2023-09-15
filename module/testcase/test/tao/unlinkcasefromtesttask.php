#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('testrun')->gen(10);

/**

title=测试 testcaseTao->unlinkCaseFromTesttask();
timeout=0
cid=1

- 测试取消测试单与用例的关联。 @0

*/

$caseID = 1;
$branch = 1;

$testcase = new testcaseTest();
r($testcase->unlinkCaseFromTesttaskTest($caseID, $branch)) && p() && e(0); //测试取消测试单与用例的关联。