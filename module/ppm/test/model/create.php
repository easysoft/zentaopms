#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::create();
timeout=0
cid=0

- 执行ppmModel模块的createTest方法，参数是$duplicatePPM 属性message @存在重复并且未关闭的合并请求: ID6401
- 执行ppmModel模块的createTest方法，参数是$sameBranchPPM 属性message @源项目分支与目标项目分支不能相同
- 执行ppmModel模块的createTest方法，参数是$missingFlowPPM 属性message @服务器错误
- 执行ppmModel模块的createTest方法，参数是$reviewerPPM 属性reviewer @评审人必须包含用户1
- 执行ppmModel模块的createTest方法，参数是$apiCreatePPM 属性apiMessage @仓库不存在。
- 执行ppmModel模块的createTest方法，参数是$noReviewerPPM 属性apiMessage @仓库不存在。
- 执行ppmModel模块的createTest方法，参数是$crossRepoBranchPPM 属性apiMessage @仓库不存在。
- 执行ppmModel模块的createTest方法，参数是$approvalFlowPPM 属性apiMessage @仓库不存在。
- 执行instance模块的fetchByID方法，参数是6401 
 - 属性status @opened
 - 属性title @Create PPM 6401
- 执行$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPM)->where('repoID')->eq(6401)->fetch('count(*)' @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('user')->gen(4);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6401-6402');
$repo->product->range('1{2}');
$repo->name->range('ppm-repo-6401,ppm-repo-6402');
$repo->gen(2);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('6401');
$ppm->title->range('Create PPM 6401');
$ppm->repoID->range('6401');
$ppm->sourceRepoID->range('6401');
$ppm->sourceBranch->range('feature/create');
$ppm->targetRepoID->range('6401');
$ppm->targetBranch->range('master');
$ppm->status->range('opened');
$ppm->createdBy->range('admin');
$ppm->gen(1);

$flow = zenData('ops_review_flow')->loadYaml('reviewflow_create', false, 2);
$flow->id->range('901');
$flow->repo->range('6401');
$flow->branchType->setNull();
$flow->name->range('Create Flow 901');
$flow->desc->range('Create Flow Description');
$flow->definition->range('{"reviewFlow":{"approvals":{"minReviewers":1,"specifiedReviewers":["admin","user1"]}}}');
$flow->status->range('enable');
$flow->deleted->range('0');
$flow->createdBy->range('admin');
$flow->createdDate->range('`2026-07-10 09:00:00`');
$flow->gen(1, true, false);

su('admin');

$ppmModel = new ppmModelTest();
$ppmModel->instance->dao->update(TABLE_REVIEWFLOW)->set('definition')->eq('{"reviewFlow":{"approvals":{"minReviewers":1,"specifiedReviewers":["admin","user1"]}}}')->where('id')->eq(901)->exec();

$duplicatePPM = (object)array(
    'title'        => 'Duplicate PPM',
    'repoID'       => 6401,
    'sourceRepoID' => 6401,
    'sourceBranch' => 'feature/create',
    'targetRepoID' => 6401,
    'targetBranch' => 'master',
);

$sameBranchPPM = (object)array(
    'title'        => 'Same Branch PPM',
    'repoID'       => 6401,
    'sourceRepoID' => 6401,
    'sourceBranch' => 'master',
    'targetRepoID' => 6401,
    'targetBranch' => 'master',
);

$missingFlowPPM = (object)array(
    'title'          => 'Missing Flow PPM',
    'repoID'         => 6401,
    'sourceRepoID'   => 6401,
    'sourceBranch'   => 'feature/missing-flow',
    'sourceSHA'      => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    'targetRepoID'   => 6401,
    'targetBranch'   => 'master',
    'mergeTargetSHA' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
    'reviewer'       => 'admin',
    'approvalflow'   => 0,
    'reviewFlowID'   => 999,
);

$reviewerPPM = (object)array(
    'title'          => 'Reviewer Flow PPM',
    'repoID'         => 6401,
    'sourceRepoID'   => 6401,
    'sourceBranch'   => 'feature/reviewer-flow',
    'sourceSHA'      => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    'targetRepoID'   => 6401,
    'targetBranch'   => 'master',
    'mergeTargetSHA' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
    'reviewer'       => 'admin',
    'approvalflow'   => 0,
    'reviewFlowID'   => 901,
);

$apiCreatePPM = (object)array(
    'title'          => 'API Create PPM',
    'repoID'         => 6401,
    'sourceRepoID'   => 6401,
    'sourceBranch'   => 'feature/api-create',
    'sourceSHA'      => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    'targetRepoID'   => 6401,
    'targetBranch'   => 'master',
    'mergeTargetSHA' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
    'createdBy'      => 'admin',
    'createdDate'    => '2026-07-10 11:00:00',
);

$noReviewerPPM = (object)array(
    'title'          => 'No Reviewer PPM',
    'repoID'         => 6401,
    'sourceRepoID'   => 6401,
    'sourceBranch'   => 'feature/no-reviewer',
    'sourceSHA'      => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    'targetRepoID'   => 6401,
    'targetBranch'   => 'master',
    'mergeTargetSHA' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
    'reviewer'       => '',
    'approvalflow'   => 0,
    'reviewFlowID'   => 0,
);

$crossRepoBranchPPM = (object)array(
    'title'          => 'Cross Repo Same Branch PPM',
    'repoID'         => 6401,
    'sourceRepoID'   => 6401,
    'sourceBranch'   => 'feature/cross-same',
    'sourceSHA'      => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    'targetRepoID'   => 6402,
    'targetBranch'   => 'feature/cross-same',
    'mergeTargetSHA' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
    'reviewer'       => '',
    'approvalflow'   => 0,
    'reviewFlowID'   => 0,
);

$approvalFlowPPM = (object)array(
    'title'          => 'Approval Flow PPM',
    'repoID'         => 6401,
    'sourceRepoID'   => 6401,
    'sourceBranch'   => 'feature/approval',
    'sourceSHA'      => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    'targetRepoID'   => 6401,
    'targetBranch'   => 'master',
    'mergeTargetSHA' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
    'reviewer'       => 'admin',
    'approvalflow'   => 1,
    'reviewFlowID'   => 0,
);

r($ppmModel->createTest($duplicatePPM)) && p('message') && e('存在重复并且未关闭的合并请求: ID6401');
r($ppmModel->createTest($sameBranchPPM)) && p('message') && e('源项目分支与目标项目分支不能相同');
r($ppmModel->createTest($missingFlowPPM)) && p('message') && e('服务器错误');
r($ppmModel->createTest($reviewerPPM)) && p('reviewer') && e('评审人必须包含用户1');
r($ppmModel->createTest($apiCreatePPM)) && p('apiMessage') && e('仓库不存在。');
r($ppmModel->createTest($noReviewerPPM)) && p('apiMessage') && e('仓库不存在。');
r($ppmModel->createTest($crossRepoBranchPPM)) && p('apiMessage') && e('仓库不存在。');
r($ppmModel->createTest($approvalFlowPPM)) && p('apiMessage') && e('仓库不存在。');
r($ppmModel->instance->fetchByID(6401)) && p('status,title') && e('opened,Create PPM 6401');
r((int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPM)->where('repoID')->eq(6401)->fetch('count(*)')) && p() && e('1');
