#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getBranchRule();
timeout=0
cid=0

- 步骤1：正常查询存在的规则属性branchName @main
- 步骤2：不存在的代码库ID @0
- 步骤3：不存在的分支类型 @0
- 步骤4：不存在的分支名称 @0
- 步骤5：所有参数为0或空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

$branchRuleSetTable = zenData('ops_branch_ruleset');
$branchRuleSetTable->id->range('1-10');
$branchRuleSetTable->repo->range('1-10');
$branchRuleSetTable->branchType->range('1-5');
$branchRuleSetTable->branchName->range('main,master,develop,release,feature');
$branchRuleSetTable->deleteUser->range('admin{5}');
$branchRuleSetTable->updateUser->range('admin{5}');
$branchRuleSetTable->forcePushUser->range('admin{5}');
$branchRuleSetTable->sourceBranch->range('``{5}');
$branchRuleSetTable->targetBranch->range('``{5}');
$branchRuleSetTable->createdBy->range('admin{5}');
$branchRuleSetTable->editedBy->range('``{5}');
$branchRuleSetTable->deleted->range('0{5}');
$branchRuleSetTable->gen(5);

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->name->range('代码库{10}');
$repo->SCM->range('Git{5},Subversion{5}');
$repo->deleted->range('0{10}');
$repo->gen(10);

su('admin');

$repoTest = new repoTest();

r($repoTest->getBranchRuleTest(1, 1, 'main')) && p('branchName') && e('main'); // 步骤1：正常查询存在的规则
r($repoTest->getBranchRuleTest(1, 999, 'main')) && p() && e('0'); // 步骤2：不存在的代码库ID
r($repoTest->getBranchRuleTest(999, 1, 'main')) && p() && e('0'); // 步骤3：不存在的分支类型
r($repoTest->getBranchRuleTest(1, 1, 'nonexist')) && p() && e('0'); // 步骤4：不存在的分支名称
r($repoTest->getBranchRuleTest(0, 0, '')) && p() && e('0'); // 步骤5：所有参数为0或空
