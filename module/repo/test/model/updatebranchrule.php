#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateBranchRule();
timeout=0
cid=0

- 测试步骤1：更新分支名称 >> 期望更新成功，branchName为main-updated
- 测试步骤2：更新权限设置 >> 期望更新成功，repo为2
- 测试步骤3：更新合并限制 >> 期望更新成功，repo为3
- 测试步骤4：清空某些权限字段 >> 期望更新成功，forcePushUser为空
- 测试步骤5：不存在的规则ID >> 期望更新失败，返回false

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

$update1 = new stdclass();
$update1->branchName = 'main-updated';
r($repoTest->updateBranchRuleTest(1, $update1)) && p('branchName') && e('main-updated'); // 步骤1：更新分支名称

$update2 = new stdclass();
$update2->deleteUser = 'admin,user1,user2';
$update2->updateUser = 'admin,user1,user2,user3';
r($repoTest->updateBranchRuleTest(2, $update2)) && p('repo') && e('2'); // 步骤2：更新权限

$update3 = new stdclass();
$update3->sourceBranch = '1,2,3';
$update3->targetBranch = '4,5';
r($repoTest->updateBranchRuleTest(3, $update3)) && p('repo') && e('3'); // 步骤3：更新合并限制

$update4 = new stdclass();
$update4->forcePushUser = '';
$update4->editedBy = 'admin';
$update4->editedDate = date('Y-m-d H:i:s');
r($repoTest->updateBranchRuleTest(4, $update4)) && p('forcePushUser') && e('~~'); // 步骤4：清空权限字段

$update5 = new stdclass();
$update5->branchName = 'nonexist';
r($repoTest->updateBranchRuleTest(999, $update5)) && p() && e('0'); // 步骤5：不存在的规则ID
