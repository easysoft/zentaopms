#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->getIssueTreeList();
timeout=0
cid=0

- 获取空仓库的文件树失败 @0
- 获取空仓库的规则树失败 @0
- 获取 1 号仓库的文件树根节点 @root
- 获取不存在任务的规则树失败 @0
- 获取不存在任务的文件树失败 @0

*/

su('admin');
$test = new codescanModelTest();

r($test->getIssueTreeListTest(0, 0, 'file')) && p() && e('0');
r($test->getIssueTreeListTest(0, 0, 'rule')) && p() && e('0');
r($test->getIssueTreeListTest(1, 0, 'file')) && p('0:name') && e('root');
r($test->getIssueTreeListTest(0, 1, 'rule')) && p() && e('0');
r($test->getIssueTreeListTest(2, 3, 'file')) && p() && e('0');
