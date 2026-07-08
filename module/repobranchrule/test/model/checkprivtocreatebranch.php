#!/usr/bin/env php
<?php
error_reporting(E_ALL & ~E_DEPRECATED);

/**

title=测试 repobranchruleModel::checkPrivToCreateBranch();
timeout=0
cid=0

- 步骤1：operator在createUser列表中 @1
- 步骤2：operator不在createUser列表中 @0
- 步骤3：带/前缀的分支名，operator在列表中 @1
- 步骤4：带/前缀的分支名，operator不在列表中 @0
- 步骤5：无匹配的分支类型规则时返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$repo = zenData('repo');
$repo->id->range('1-5');
$repo->name->range('代码库{5}');
$repo->SCM->range('Git{5}');
$repo->deleted->range('0{5}');
$repo->gen(5);

$branchTypeTable = zenData('ops_branch_type');
$branchTypeTable->id->range('1-5');
$branchTypeTable->repo->range('1,1,2,2,3');
$branchTypeTable->name->range('feature,hotfix,release,bugfix,develop');
$branchTypeTable->key->range('feature,hotfix,release,bugfix,develop');
$branchTypeTable->prefix->range('feature/,hotfix/,release/,bugfix/,develop');
$branchTypeTable->deleted->range('0{5}');
$branchTypeTable->gen(5);

$branchRuleSetTable = zenData('ops_branch_ruleset');
$branchRuleSetTable->id->range('1-5');
$branchRuleSetTable->repo->range('1,1,2,2,3');
$branchRuleSetTable->branchType->range('1,2,3,4,5');
$branchRuleSetTable->branchName->range('[]{5}');
$branchRuleSetTable->createUser->range('admin,user1,admin,user2,');
$branchRuleSetTable->deleteUser->range('admin{5}');
$branchRuleSetTable->updateUser->range('admin{5}');
$branchRuleSetTable->forcePushUser->range('admin{5}');
$branchRuleSetTable->sourceBranch->range('[]{5}');
$branchRuleSetTable->targetBranch->range('[]{5}');
$branchRuleSetTable->createdBy->range('admin{5}');
$branchRuleSetTable->editedBy->range('[]{5}');
$branchRuleSetTable->deleted->range('0{5}');
$branchRuleSetTable->gen(5);

su('admin');

$repoTest = new repobranchruleTest();

r($repoTest->checkPrivToCreateBranchTest(1, 'feature/', 'admin')) && p() && e('1'); // 步骤1：operator在createUser列表中
r($repoTest->checkPrivToCreateBranchTest(1, 'feature/', 'user2')) && p() && e('0'); // 步骤2：operator不在createUser列表中
r($repoTest->checkPrivToCreateBranchTest(1, 'feature/new-feature', 'admin')) && p() && e('1'); // 步骤3：带/前缀的分支名，operator在列表中
r($repoTest->checkPrivToCreateBranchTest(1, 'feature/new-feature', 'user2')) && p() && e('0'); // 步骤4：带/前缀的分支名，operator不在列表中
r($repoTest->checkPrivToCreateBranchTest(4, 'main', 'anyone')) && p() && e('1'); // 步骤5：无匹配的分支类型规则时返回true
