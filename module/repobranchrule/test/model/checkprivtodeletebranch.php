#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleModel::checkPrivToDeleteBranch();
timeout=0
cid=0

- 步骤1：具体分支规则，operator在deleteUser列表中 @1
- 步骤2：具体分支规则，operator不在deleteUser列表中 @0
- 步骤3：分支类型规则，operator在deleteUser列表中 @1
- 步骤4：分支类型规则，operator不在deleteUser列表中 @0
- 步骤5：无匹配规则时返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('1-5');
$repo->name->range('代码库{5}');
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
$branchRuleSetTable->id->range('1-6');
$branchRuleSetTable->repo->range('1,1,1,2,2,3');
$branchRuleSetTable->branchType->range('0,1,2,3,4,5');
$branchRuleSetTable->branchName->range('main,``,``,``,``,``');
$branchRuleSetTable->createUser->range('admin{6}');
$branchRuleSetTable->deleteUser->range('admin,admin,user1,admin,user2,');
$branchRuleSetTable->updateUser->range('admin{6}');
$branchRuleSetTable->forcePushUser->range('admin{6}');
$branchRuleSetTable->sourceBranch->range('``{6}');
$branchRuleSetTable->targetBranch->range('``{6}');
$branchRuleSetTable->createdBy->range('admin{6}');
$branchRuleSetTable->editedBy->range('``{6}');
$branchRuleSetTable->deleted->range('0{6}');
$branchRuleSetTable->gen(6);

su('admin');

$repoTest = new repobranchruleTest();

r($repoTest->checkPrivToDeleteBranchTest(1, 'main', 'admin')) && p() && e('1'); // 步骤1：具体分支规则，operator在deleteUser列表中
r($repoTest->checkPrivToDeleteBranchTest(1, 'main', 'user2')) && p() && e('0'); // 步骤2：具体分支规则，operator不在deleteUser列表中
r($repoTest->checkPrivToDeleteBranchTest(1, 'feature/test', 'admin')) && p() && e('1'); // 步骤3：分支类型规则，operator在deleteUser列表中
r($repoTest->checkPrivToDeleteBranchTest(1, 'hotfix/fix', 'admin2')) && p() && e('0'); // 步骤4：分支类型规则，operator不在deleteUser列表中
r($repoTest->checkPrivToDeleteBranchTest(4, 'unknown', 'anyone')) && p() && e('1'); // 步骤5：无匹配规则时返回true
