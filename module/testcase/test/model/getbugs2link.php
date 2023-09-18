#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('bug')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 testcaseModel->getCases2Link();
timeout=0
cid=1

- 获取相关的 bug @4

*/

$caseIDList     = array(1, 2, 3);
$browseTypeList = array('bySearch', '');

$testcase = new testcaseTest();

r($testcase->getBugs2LinkTest($caseIDList[0], $browseTypeList[0])) && p() && e('1,2,3'); // 获取相关的 case 1 browse bySearch bug
r($testcase->getBugs2LinkTest($caseIDList[1], $browseTypeList[0])) && p() && e('1,2,3'); // 获取相关的 case 2 browse bySearch bug
r($testcase->getBugs2LinkTest($caseIDList[2], $browseTypeList[0])) && p() && e('1,2,3'); // 获取相关的 case 3 browse bySearch bug

r($testcase->getBugs2LinkTest($caseIDList[0], $browseTypeList[1])) && p() && e('0'); // 获取相关的 case 1 browse 空 bug
r($testcase->getBugs2LinkTest($caseIDList[1], $browseTypeList[1])) && p() && e('0'); // 获取相关的 case 2 browse 空 bug
r($testcase->getBugs2LinkTest($caseIDList[2], $browseTypeList[1])) && p() && e('0'); // 获取相关的 case 3 browse 空 bug
