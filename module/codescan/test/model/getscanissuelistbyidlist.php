#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->getScanIssueListByIdList();
timeout=0
cid=0

- 查询单个问题 ID 返回空列表 @0
- 查询两个问题 ID 返回空列表 @0
- 查询空问题 ID 列表直接返回成功 @1
- 查询不存在的问题 ID 返回空列表 @0
- 查询零号问题 ID 返回空列表 @0

*/

su('admin');
$test = new codescanModelTest();

r($test->getScanIssueListByIdListTest(array(1))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array(1, 2))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array())) && p() && e('1');
r($test->getScanIssueListByIdListTest(array(100, 101, 102))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array(0))) && p() && e('0');
