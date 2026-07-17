#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleModel::getList();
timeout=0
cid=0

- 测试repo=1获取所有规则 >> 验证3条记录 @3
- 测试repo=1按branchType过滤 >> 验证2条记录 @2
- 测试repo=1按branchName过滤 >> 验证1条 @1
- 测试repo=999不存在 >> 验证0条 @0
- 测试repo=0 >> 验证0条 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repobranchrule.unittest.class.php';

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('1-3');
$repo->name->range('代码库{3}');
$repo->gen(3);

$branchRuleSetTable = zenData('ops_branch_ruleset');
$branchRuleSetTable->id->range('1-5');
$branchRuleSetTable->repo->range('1,1,1,2,2');
$branchRuleSetTable->branchType->range('1,2,3,1,2');
$branchRuleSetTable->branchName->range('main,feature,develop,release,hotfix');
$branchRuleSetTable->createUser->range('admin{5}');
$branchRuleSetTable->deleteUser->range('admin{5}');
$branchRuleSetTable->updateUser->range('admin{5}');
$branchRuleSetTable->forcePushUser->range('admin{5}');
$branchRuleSetTable->sourceBranch->range('[]{5}');
$branchRuleSetTable->targetBranch->range('[]{5}');
$branchRuleSetTable->createdBy->range('admin{5}');
$branchRuleSetTable->deleted->range('0{5}');
$branchRuleSetTable->gen(5);

su('admin');

$tester = new repobranchruleTest();

r(count($tester->getListTest(1))) && p() && e('3');
r(count($tester->getListTest(1, array(1, 2)))) && p() && e('2');
r(count($tester->getListTest(1, array(), array('main')))) && p() && e('1');
r(count($tester->getListTest(999))) && p() && e('0');
r(count($tester->getListTest(0))) && p() && e('0');
