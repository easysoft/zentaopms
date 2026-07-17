#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleModel::getRuleByBranchName();
timeout=0
cid=0

- 测试按分支名查询规则(repo=1, main) 属性branchName @main
- 测试按分支名查询规则(repo=1, feature) 属性branchName @feature
- 测试按分支名查询规则(repo=1, develop) 属性branchName @develop
- 测试不存在的分支名 >> 验证返回空 @0
- 测试不存在的repo >> 验证返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('1-3');
$repo->name->range('代码库{3}');
$repo->gen(3);

$branchRuleSetTable = zenData('ops_branch_ruleset');
$branchRuleSetTable->id->range('1-3');
$branchRuleSetTable->repo->range('1,1,1');
$branchRuleSetTable->branchType->range('0,0,0');
$branchRuleSetTable->branchName->range('main,feature,develop');
$branchRuleSetTable->createUser->range('admin{3}');
$branchRuleSetTable->deleteUser->range('admin{3}');
$branchRuleSetTable->updateUser->range('admin{3}');
$branchRuleSetTable->forcePushUser->range('admin{3}');
$branchRuleSetTable->sourceBranch->range('[]{3}');
$branchRuleSetTable->targetBranch->range('[]{3}');
$branchRuleSetTable->createdBy->range('admin{3}');
$branchRuleSetTable->deleted->range('0{3}');
$branchRuleSetTable->gen(3);

su('admin');

$tester = new repobranchruleTest();

$r1 = $tester->getRuleByBranchNameTest(1, 'main');
$r2 = $tester->getRuleByBranchNameTest(1, 'feature');
$r3 = $tester->getRuleByBranchNameTest(1, 'develop');
$r4 = $tester->getRuleByBranchNameTest(1, 'nonexistent');
$r5 = $tester->getRuleByBranchNameTest(999, 'main');

r($r1) && p('branchName') && e('main');
r($r2) && p('branchName') && e('feature');
r($r3) && p('branchName') && e('develop');
r((!$r4 || (is_array($r4) && empty($r4))) ? 1 : 0) && p() && e('1');
r((!$r5 || (is_array($r5) && empty($r5))) ? 1 : 0) && p() && e('1');
