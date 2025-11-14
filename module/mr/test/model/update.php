#!/usr/bin/env php
<?php

/**

title=测试 mrModel::update();
timeout=0
cid=17261

- 测试步骤1：正常更新MR数据属性result @success
- 测试步骤2：使用不存在的MRID属性message @此合并请求不存在。
- 测试步骤3：需要CI但jobID为空属性message @『流水线任务』不能为空。
- 测试步骤4：标题为空属性message @『名称』不能为空。
- 测试步骤5：源分支和目标分支相同属性message @源项目分支与目标项目分支不能相同

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(1);
zenData('repo')->loadYaml('repo')->gen(1);
zenData('mr')->loadYaml('mr')->gen(1);

su('admin');

$mrTest = new mrTest();

$validMR = new stdclass();
$validMR->title              = 'Updated test merge request';
$validMR->assignee           = 'admin';
$validMR->repoID             = 1;
$validMR->needCI             = 0;
$validMR->removeSourceBranch = 0;
$validMR->targetBranch       = 'master';
$validMR->sourceBranch       = 'develop';
$validMR->squash             = 0;
$validMR->jobID              = 0;
$validMR->description        = 'Updated description for test merge';
$validMR->editedBy           = 'admin';
$validMR->editedDate         = date('Y-m-d H:i:s');

r($mrTest->updateTester(1, $validMR)) && p('result') && e('success'); // 测试步骤1：正常更新MR数据

r($mrTest->updateTester(999, $validMR)) && p('message') && e('此合并请求不存在。'); // 测试步骤2：使用不存在的MRID

$mrWithCI = clone $validMR;
$mrWithCI->needCI = 1;
$mrWithCI->jobID = 0;
r($mrTest->updateTester(1, $mrWithCI)) && p('message') && e('『流水线任务』不能为空。'); // 测试步骤3：需要CI但jobID为空

$mrWithEmptyTitle = clone $validMR;
$mrWithEmptyTitle->title = '';
r($mrTest->updateTester(1, $mrWithEmptyTitle)) && p('message') && e('『名称』不能为空。'); // 测试步骤4：标题为空

$mrWithSameBranch = clone $validMR;
$mrWithSameBranch->sourceBranch = 'master';
$mrWithSameBranch->targetBranch = 'master';
r($mrTest->updateTester(1, $mrWithSameBranch)) && p('message') && e('源项目分支与目标项目分支不能相同'); // 测试步骤5：源分支和目标分支相同