#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::insertMr();
timeout=0
cid=0

- 执行$firstID > 6103 @1
- 执行instance模块的fetchByID方法，参数是$firstID 属性title @Inserted PPM
- 执行$secondID > $firstID @1
- 执行instance模块的fetchByID方法，参数是$secondID 属性title @Inserted PPM 2
- 执行$afterCount - $beforeCount @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(3);
zenData('product')->gen(2);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6101-6102');
$repo->product->range('1,2');
$repo->name->range('ppm-repo-6101,ppm-repo-6102');
$repo->gen(2);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('6101-6103');
$ppm->title->range('Opened PPM 6101,Closed PPM 6102,Review PPM 6103');
$ppm->repoID->range('6101,6102,6101');
$ppm->sourceRepoID->range('6101,6102,6101');
$ppm->sourceBranch->range('feature/basic-opened,feature/basic-closed,feature/basic-review');
$ppm->targetRepoID->range('6101,6102,6101');
$ppm->targetBranch->range('master{3}');
$ppm->status->range('opened,closed,opened');
$ppm->createdBy->range('admin,user1,admin');
$ppm->reviewers->range('admin,user1,admin');
$ppm->reviewStatus->range('pending,approved,rejected');
$ppm->gen(3);

su('admin');

$ppmModel = new ppmModelTest();

$firstPPM = (object)array(
    'title'          => 'Inserted PPM',
    'desc'           => 'Inserted description',
    'repoID'         => 6101,
    'sourceRepoID'   => 6101,
    'sourceBranch'   => 'feature/inserted',
    'sourceSHA'      => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    'targetRepoID'   => 6101,
    'targetBranch'   => 'master',
    'mergeTargetSHA' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
    'mergeBaseSHA'   => 'cccccccccccccccccccccccccccccccccccccccc',
    'mergeSHA'       => 'dddddddddddddddddddddddddddddddddddddddd',
    'status'         => 'opened',
    'createdBy'      => 'admin',
    'createdDate'    => '2026-07-10 10:00:00',
    'reviewStatus'   => 'pending',
    'approvalflow'   => 0,
    'reviewFlowID'   => 0,
);

$secondPPM = clone $firstPPM;
$secondPPM->title        = 'Inserted PPM 2';
$secondPPM->sourceBranch = 'feature/inserted-2';

$beforeCount = (int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPM)->fetch('count(*)');
$firstID     = (int)$ppmModel->insertMrTest($firstPPM);
$secondID    = (int)$ppmModel->insertMrTest($secondPPM);
$afterCount  = (int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPM)->fetch('count(*)');

r($firstID > 6103) && p() && e('1');
r($ppmModel->instance->fetchByID($firstID)) && p('title') && e('Inserted PPM');
r($secondID > $firstID) && p() && e('1');
r($ppmModel->instance->fetchByID($secondID)) && p('title') && e('Inserted PPM 2');
r($afterCount - $beforeCount) && p() && e('2');