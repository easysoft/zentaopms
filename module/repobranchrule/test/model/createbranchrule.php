#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleModel::createBranchRule();
timeout=0
cid=0

- 步骤1：完整规则创建属性branchName @feature
- 步骤2：最小字段创建属性branchName @hotfix
- 步骤3：权限设置属性branchName @release
- 步骤4：合并限制属性branchName @develop
- 步骤5：空权限属性forcePushUser @~~
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->name->range('代码库{10}');
$repo->SCM->range('Git{5},Subversion{5}');
$repo->deleted->range('0{10}');
$repo->gen(10);

su('admin');

$repoTest = new repobranchruleTest();

$rule1 = new stdclass();
$rule1->repo = 1;
$rule1->branchType = 1;
$rule1->branchName = 'feature';
$rule1->deleteUser = 'admin';
$rule1->updateUser = 'admin,user1';
$rule1->forcePushUser = 'admin';
$rule1->sourceBranch = '1,2';
$rule1->targetBranch = '1';
$rule1->createdBy = 'admin';
$rule1->createdDate = date('Y-m-d H:i:s');
r($repoTest->createBranchRuleTest($rule1)) && p('branchName') && e('feature'); // 步骤1：完整规则创建

$rule2 = new stdclass();
$rule2->repo = 2;
$rule2->branchType = 2;
$rule2->branchName = 'hotfix';
$rule2->deleteUser = '';
$rule2->updateUser = '';
$rule2->forcePushUser = '';
$rule2->sourceBranch = '';
$rule2->targetBranch = '';
r($repoTest->createBranchRuleTest($rule2)) && p('branchName') && e('hotfix'); // 步骤2：最小字段创建

$rule3 = new stdclass();
$rule3->repo = 3;
$rule3->branchType = 3;
$rule3->branchName = 'release';
$rule3->deleteUser = 'admin,user1';
$rule3->updateUser = 'admin,user1,user2';
$rule3->forcePushUser = 'admin';
$rule3->sourceBranch = '';
$rule3->targetBranch = '';
r($repoTest->createBranchRuleTest($rule3)) && p('branchName') && e('release'); // 步骤3：权限设置

$rule4 = new stdclass();
$rule4->repo = 4;
$rule4->branchType = 1;
$rule4->branchName = 'develop';
$rule4->deleteUser = 'admin';
$rule4->updateUser = 'admin';
$rule4->forcePushUser = '';
$rule4->sourceBranch = '1,2';
$rule4->targetBranch = '3,4';
r($repoTest->createBranchRuleTest($rule4)) && p('branchName') && e('develop'); // 步骤4：合并限制

$rule5 = new stdclass();
$rule5->repo = 5;
$rule5->branchType = 2;
$rule5->branchName = 'bugfix';
$rule5->deleteUser = 'admin';
$rule5->updateUser = 'admin';
$rule5->forcePushUser = '';
$rule5->sourceBranch = '';
$rule5->targetBranch = '';
r($repoTest->createBranchRuleTest($rule5)) && p('forcePushUser') && e('~~'); // 步骤5：空权限
