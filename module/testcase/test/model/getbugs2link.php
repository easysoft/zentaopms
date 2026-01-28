#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1{5},2{5}');
$bug->project->range('0');
$bug->execution->range('0');
$bug->title->range('bug名称')->prefix('1-10');
$bug->gen(10);

zenData('user')->gen(1);
zenData('case')->gen(5);
zenData('story')->gen(5);

su('admin');

/**

title=测试 testcaseModel->getCases2Link();
timeout=0
cid=18974

- 获取相关的 case 1 browse bySearch bug @1,2,3

- 获取相关的 case 2 browse bySearch bug @1,2,3

- 获取相关的 case 3 browse bySearch bug @1,2,3

- 获取相关的 case 1 browse 空 bug @0
- 获取相关的 case 2 browse 空 bug @0
- 获取相关的 case 3 browse 空 bug @0

*/

global $tester;
$caseIDList     = array(1, 2, 3);
$browseTypeList = array('bySearch', '');

$testcase = new testcaseModelTest();

r($testcase->getBugs2LinkTest($caseIDList[0], $browseTypeList[0])) && p() && e('1,2,3,4,5'); // 获取相关的 case 1 browse bySearch bug
r($testcase->getBugs2LinkTest($caseIDList[1], $browseTypeList[0])) && p() && e('1,2,3,4,5'); // 获取相关的 case 2 browse bySearch bug
r($testcase->getBugs2LinkTest($caseIDList[2], $browseTypeList[0])) && p() && e('1,2,3,4,5'); // 获取相关的 case 3 browse bySearch bug

r($testcase->getBugs2LinkTest($caseIDList[0], $browseTypeList[1])) && p() && e('0'); // 获取相关的 case 1 browse 空 bug
r($testcase->getBugs2LinkTest($caseIDList[1], $browseTypeList[1])) && p() && e('0'); // 获取相关的 case 2 browse 空 bug
r($testcase->getBugs2LinkTest($caseIDList[2], $browseTypeList[1])) && p() && e('0'); // 获取相关的 case 3 browse 空 bug