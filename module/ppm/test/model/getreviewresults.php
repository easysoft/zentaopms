#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getReviewResults();
timeout=0
cid=0

- 执行ppmModel模块的getReviewResultsTest方法，参数是array 第6301条的result属性 @approved
- 执行ppmModel模块的getReviewResultsTest方法，参数是array  @2
- 执行ppmModel模块的getReviewResultsTest方法，参数是array 第6301条的result属性 @rejected
- 执行ppmModel模块的getReviewResultsTest方法，参数是array 第6301条的result属性 @inProgress
- 执行ppmModel模块的getReviewResultsTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(4);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6301');
$repo->product->range('1');
$repo->name->range('ppm-repo-6301');
$repo->gen(1);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('6301-6302');
$ppm->title->range('Review PPM 6301,Review PPM 6302');
$ppm->repoID->range('6301{2}');
$ppm->sourceRepoID->range('6301{2}');
$ppm->sourceBranch->range('feature/review,feature/closed');
$ppm->targetRepoID->range('6301{2}');
$ppm->targetBranch->range('release/main,release/main');
$ppm->status->range('opened,closed');
$ppm->createdBy->range('admin,admin');
$ppm->reviewFlowID->range('901{2}');
$ppm->gen(2);

$reviewers = zenData('ops_request_reviewers')->loadYaml('ops_request_reviewers', false, 2);
$reviewers->id->range('1-2');
$reviewers->requestID->range('6301{2}');
$reviewers->repoID->range('6301{2}');
$reviewers->account->range('admin,user1');
$reviewers->decision->range('approved,approved');
$reviewers->gen(2);

$flow = zenData('ops_review_flow')->loadYaml('reviewflow_getreviewresults', false, 2);
$flow->id->range('901');
$flow->repo->range('6301');
$flow->branchType->setNull();
$flow->name->range('PPM Flow 901');
$flow->desc->range('PPM Flow Description');
$flow->definition->range('{"reviewFlow":{"approvals":{"minReviewers":2}}}');
$flow->status->range('enable');
$flow->deleted->range('0');
$flow->createdBy->range('admin');
$flow->createdDate->range('`2026-07-10 09:00:00`');
$flow->gen(1, true, false);

su('admin');

$ppmModel = new ppmModelTest();
$ppmModel->instance->dao->update(TABLE_REVIEWFLOW)->set('definition')->eq('{"reviewFlow":{"approvals":{"minReviewers":2}}}')->where('id')->eq(901)->exec();

r($ppmModel->getReviewResultsTest(array(6301), 6301)) && p('6301:result') && e('approved');
r(count($ppmModel->getReviewResultsTest(array(6301), 6301)[6301]['reviewers'])) && p() && e('2');
$ppmModel->instance->dao->update(TABLE_PPMREVIEWERS)->set('decision')->eq('rejected')->where('requestID')->eq(6301)->andWhere('account')->eq('admin')->exec();
r($ppmModel->getReviewResultsTest(array(6301), 6301)) && p('6301:result') && e('rejected');
$ppmModel->instance->dao->update(TABLE_PPMREVIEWERS)->set('decision')->eq('approved')->where('requestID')->eq(6301)->andWhere('account')->eq('admin')->exec();
$ppmModel->instance->dao->update(TABLE_PPMREVIEWERS)->set('decision')->eq('pending')->where('requestID')->eq(6301)->andWhere('account')->eq('user1')->exec();
r($ppmModel->getReviewResultsTest(array(6301), 6301)) && p('6301:result') && e('inProgress');
r($ppmModel->getReviewResultsTest(array(6302), 6301)) && p() && e('0');