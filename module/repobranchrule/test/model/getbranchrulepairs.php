#!/usr/bin/env php
<?php

/**

title=测试 repobranchModel::getBranchRulePairs();
timeout=0
cid=0

- 步骤1：查询repoID=1的createUser字段 @admin,admin
- 步骤2：查询repoID=1的deleteUser字段 @user1,user2
- 步骤3：查询repoID=2的createUser字段 @admin,user1
- 步骤4：查询不存在的repoID @0
- 步骤5：查询repoID=1的updateUser字段 @admin,admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('1-5');
$repo->name->range('代码库{5}');
$repo->gen(5);

$branchRuleSetTable = zenData('ops_branch_ruleset');
$branchRuleSetTable->id->range('1-5');
$branchRuleSetTable->repo->range('1,1,2,2,3');
$branchRuleSetTable->branchType->range('1,2,3,4,5');
$branchRuleSetTable->branchName->range('[]{5}');
$branchRuleSetTable->createUser->range('admin,admin,admin,user1,user2');
$branchRuleSetTable->deleteUser->range('user1,user2,admin,admin,admin');
$branchRuleSetTable->updateUser->range('admin,admin,user1,user1,user2');
$branchRuleSetTable->forcePushUser->range('admin{5}');
$branchRuleSetTable->sourceBranch->range('[]{5}');
$branchRuleSetTable->targetBranch->range('[]{5}');
$branchRuleSetTable->createdBy->range('admin{5}');
$branchRuleSetTable->editedBy->range('[]{5}');
$branchRuleSetTable->deleted->range('0{5}');
$branchRuleSetTable->gen(5);

su('admin');

$repoTest = new repobranchruleTest();

r($repoTest->getBranchRulePairsTest(1, 'branchType', 'createUser')) && p() && e('admin,admin'); // 步骤1：查询repoID=1的createUser字段
r($repoTest->getBranchRulePairsTest(1, 'branchType', 'deleteUser')) && p() && e('user1,user2'); // 步骤2：查询repoID=1的deleteUser字段
r($repoTest->getBranchRulePairsTest(2, 'branchType', 'createUser')) && p() && e('admin,user1'); // 步骤3：查询repoID=2的createUser字段
r($repoTest->getBranchRulePairsTest(999, 'branchType', 'createUser')) && p() && e('0'); // 步骤4：查询不存在的repoID
r($repoTest->getBranchRulePairsTest(1, 'branchType', 'updateUser')) && p() && e('admin,admin'); // 步骤5：查询repoID=1的updateUser字段
