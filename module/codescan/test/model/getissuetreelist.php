#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->getIssueTreeList();
timeout=0
cid=0

- 获取 file 树的空仓库结果 @0
- 获取 rule 树的空仓库结果 @0
- 获取 file 树的 1 号仓库结果 @0
- 获取 rule 树的 1 号任务结果 @0
- 获取 file 树的 2 号仓库 3 号任务结果 @0

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->getIssueTreeListTest(0, 0, 'file')) && p() && e('0');
r($test->getIssueTreeListTest(0, 0, 'rule')) && p() && e('0');
r($test->getIssueTreeListTest(1, 0, 'file')) && p() && e('0');
r($test->getIssueTreeListTest(0, 1, 'rule')) && p() && e('0');
r($test->getIssueTreeListTest(2, 3, 'file')) && p() && e('0');
