#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleModel::getBranchRule();
timeout=0
cid=0

- 步骤1：typeID不为0时按branchType查询属性branchName @main
- 步骤2：typeID不为0时按branchType查询属性branchType @2
- 步骤3：typeID为0时按repo和branchName查询属性branchName @develop
- 步骤4：typeID不为0但branchType不存在 @0
- 步骤5：typeID为0但repo或branchName不存在 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$branchRuleSetTable = zenData('ops_branch_ruleset');
$branchRuleSetTable->id->range('1-10');
$branchRuleSetTable->repo->range('1,2,3,1,2');
$branchRuleSetTable->branchType->range('1,2,3,4,5');
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

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('1-10');
$repo->name->range('代码库{10}');
$repo->gen(10);

su('admin');

$repoTest = new repobranchruleTest();

r($repoTest->getBranchRuleTest(1, 1, 'main')) && p('branchName') && e('main'); // 步骤1：按branchType+branchName查询
r($repoTest->getBranchRuleTest(2, 2, 'master')) && p('branchType') && e('2'); // 步骤2：按branchType+branchName查询
r($repoTest->getBranchRuleTest(3, 3, 'develop')) && p('branchName') && e('develop'); // 步骤3：按repo和branchName查询
r($repoTest->getBranchRuleTest(999, 1, 'main')) && p() && e('0'); // 步骤4：不存在的branchType
r($repoTest->getBranchRuleTest(1, 999, 'main')) && p() && e('0'); // 步骤5：不存在的repo
