#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::update();
timeout=0
cid=0

- 执行ppmModel模块的updateTest方法，参数是9999, $updatePPM 属性message @此评审请求不存在。
- 执行ppmModel模块的updateTest方法，参数是6401, $emptyTitlePPM 属性result @fail
- 执行ppmModel模块的updateTest方法，参数是6401, $emptyHostPPM 属性result @fail
- 执行ppmModel模块的updateTest方法，参数是6401, $updatePPM), JSON_UNESCAPED_UNICODE), 'Unknown column') !== false ? 1 : 0  @1
- 执行instance模块的fetchByID方法，参数是6401
 - 属性title @Create PPM 6401
 - 属性sourceBranch @feature/create

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('user')->gen(4);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6401');
$repo->product->range('1');
$repo->name->range('ppm-repo-6401');
$repo->gen(1);

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

su('admin');

$ppmModel = new ppmModelTest();

$updatePPM = (object)array(
    'title'         => 'Updated PPM 6401',
    'repoID'        => 6401,
    'hostID'        => 1,
    'sourceProject' => 'src/project',
    'sourceBranch'  => 'feature/create-updated',
    'targetProject' => 'dest/project',
    'targetBranch'  => 'master',
);

$emptyTitlePPM = clone $updatePPM;
$emptyTitlePPM->title = '';

$emptyHostPPM = clone $updatePPM;
$emptyHostPPM->hostID = '';

r($ppmModel->updateTest(9999, $updatePPM)) && p('message') && e('此评审请求不存在。');
r($ppmModel->updateTest(6401, $emptyTitlePPM)) && p('result') && e('fail');
r($ppmModel->updateTest(6401, $emptyHostPPM)) && p('result') && e('fail');
r(strpos(json_encode($ppmModel->updateTest(6401, $updatePPM), JSON_UNESCAPED_UNICODE), 'Unknown column') !== false ? 1 : 0) && p() && e('1');
r($ppmModel->instance->fetchByID(6401)) && p('title,sourceBranch') && e('Create PPM 6401,feature/create');