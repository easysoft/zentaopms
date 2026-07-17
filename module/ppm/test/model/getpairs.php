#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getPairs();
timeout=0
cid=0

- 执行ppmModel模块的getPairsTest方法，参数是6101
 - 属性6101 @Opened PPM 6101
 - 属性6103 @Review PPM 6103
- 执行ppmModel模块的getPairsTest方法，参数是6102 属性6102 @Closed PPM 6102
- 执行ppmModel模块的getPairsTest方法，参数是9999  @0
- 执行ppmModel模块的getPairsTest方法  @0
- 执行ppmModel模块的getPairsTest方法，参数是6101  @2

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

r($ppmModel->getPairsTest(6101)) && p('6101,6103') && e('Opened PPM 6101,Review PPM 6103');
r($ppmModel->getPairsTest(6102)) && p('6102') && e('Closed PPM 6102');
r($ppmModel->getPairsTest(9999)) && p() && e('0');
r($ppmModel->getPairsTest(0)) && p() && e('0');
r(count($ppmModel->getPairsTest(6101))) && p() && e('2');