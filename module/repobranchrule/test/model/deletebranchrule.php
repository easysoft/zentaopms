#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleModel::deleteBranchRule();
timeout=0
cid=0

- 步骤1：删除ID=1的规则 @1
- 步骤2：删除ID=2的规则 @1
- 步骤3：删除ID=3的规则 @1
- 步骤4：删除不存在的规则 @0
- 步骤5：删除已删除的规则 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$branchRuleSetTable = zenData('ops_branch_ruleset');
$branchRuleSetTable->id->range('1-10');
$branchRuleSetTable->repo->range('1-10');
$branchRuleSetTable->branchType->range('1-5');
$branchRuleSetTable->branchName->range('main,master,develop,release,feature');
$branchRuleSetTable->createUser->range('admin{5}');
$branchRuleSetTable->deleteUser->range('admin{5}');
$branchRuleSetTable->updateUser->range('admin{5}');
$branchRuleSetTable->forcePushUser->range('admin{5}');
$branchRuleSetTable->sourceBranch->range('[]{5}');
$branchRuleSetTable->targetBranch->range('[]{5}');
$branchRuleSetTable->createdBy->range('admin{5}');
$branchRuleSetTable->editedBy->range('[]{5}');
$branchRuleSetTable->deleted->range('0{5}');
$branchRuleSetTable->gen(5);

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->name->range('代码库{10}');
$repo->SCM->range('Git{5},Subversion{5}');
$repo->deleted->range('0{10}');
$repo->gen(10);

su('admin');

$repoTest = new repobranchruleTest();

r($repoTest->deleteBranchRuleTest(1)) && p() && e('1'); // 步骤1：删除ID=1的规则
r($repoTest->deleteBranchRuleTest(2)) && p() && e('1'); // 步骤2：删除ID=2的规则
r($repoTest->deleteBranchRuleTest(3)) && p() && e('1'); // 步骤3：删除ID=3的规则
r($repoTest->deleteBranchRuleTest(999)) && p() && e('0'); // 步骤4：删除不存在的规则
r($repoTest->deleteBranchRuleTest(1)) && p() && e('0'); // 步骤5：删除已删除的规则
