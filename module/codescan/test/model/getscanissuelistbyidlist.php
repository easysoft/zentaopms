#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->getScanIssueListByIdList();
timeout=0
cid=0

- 查询单个问题ID @1,1,boolean,0
- 查询两个问题ID @1,2,2,boolean,0
- 查询空问题列表 @empty,0,boolean,0
- 查询三个较大问题ID @100,101,102,3,boolean,0
- 查询零号问题ID @0,1,boolean,0

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->getScanIssueListByIdListTest(array(1))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array(1, 2))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array())) && p() && e('0');
r($test->getScanIssueListByIdListTest(array(100, 101, 102))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array(0))) && p() && e('0');
