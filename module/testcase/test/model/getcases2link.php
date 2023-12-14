#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('case')->gen('100');

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
