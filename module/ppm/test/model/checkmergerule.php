#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::checkMergeRule();
timeout=0
cid=0

- 执行ppmModel模块的checkMergeRuleTest方法，参数是6301, 'feature/demo', 'release/main')['feature/demo']['result'] ? 1 : 0  @1
- 执行ppmModel模块的checkMergeRuleTest方法，参数是6301, 'feature/demo', 'release/main')['release/main']['result'] ? 1 : 0  @1
- 执行$featureToHotfix['feature/demo']['branchType'][2] @Release
- 执行$hotfixToRelease['release/main']['branchType'][1] @Feature
- 执行$unknownBranches['unknown/source']['result'] ? '1' : '0') . ',' . ($unknownBranches['unknown/target']['result'] ? '1' : '0' @1,1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$branchType = zenData('ops_branch_type');
$branchType->id->range('1-3');
$branchType->repo->range('6301{3}');
$branchType->name->range('Feature,Release,Hotfix');
$branchType->key->range('feature,release,hotfix');
$branchType->prefix->range('feature/,release/,hotfix/');
$branchType->desc->range('Feature,Release,Hotfix');
$branchType->createdBy->range('admin{3}');
$branchType->deleted->range('0{3}');
$branchType->gen(3);

$branchRule = zenData('ops_branch_ruleset');
$branchRule->id->range('1-2');
$branchRule->repo->range('6301{2}');
$branchRule->branchType->range('1,2');
$branchRule->branchName->range(',');
$branchRule->createUser->range(',');
$branchRule->deleteUser->range(',');
$branchRule->updateUser->range(',');
$branchRule->forcePushUser->range(',');
$branchRule->ppmCreateUser->range(',');
$branchRule->ppmHandleUser->range(',');
$branchRule->targetBranch->range('2,');
$branchRule->sourceBranch->range(',1');
$branchRule->commitLine->range('0{2}');
$branchRule->pushLine->range('0{2}');
$branchRule->forceReview->range('0{2}');
$branchRule->reviewFlowID->range('901{2}');
$branchRule->createdBy->range('admin{2}');
$branchRule->deleted->range('0{2}');
$branchRule->gen(2);

su('admin');

$ppmModel         = new ppmModelTest();
$featureToHotfix  = $ppmModel->checkMergeRuleTest(6301, 'feature/demo', 'hotfix/main');
$hotfixToRelease  = $ppmModel->checkMergeRuleTest(6301, 'hotfix/demo', 'release/main');
$unknownBranches  = $ppmModel->checkMergeRuleTest(6301, 'unknown/source', 'unknown/target');

r($ppmModel->checkMergeRuleTest(6301, 'feature/demo', 'release/main')['feature/demo']['result'] ? 1 : 0) && p() && e('1');
r($ppmModel->checkMergeRuleTest(6301, 'feature/demo', 'release/main')['release/main']['result'] ? 1 : 0) && p() && e('1');
r($featureToHotfix['feature/demo']['branchType'][2]) && p() && e('Release');
r($hotfixToRelease['release/main']['branchType'][1]) && p() && e('Feature');
r(($unknownBranches['unknown/source']['result'] ? '1' : '0') . ',' . ($unknownBranches['unknown/target']['result'] ? '1' : '0')) && p() && e('1,1');