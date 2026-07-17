#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::checkSameOpened();
timeout=0
cid=0

- 执行ppmModel模块的checkSameOpenedTest方法，参数是6101, 6101, 'master', 6101, 'master' 属性result @fail
- 执行ppmModel模块的checkSameOpenedTest方法，参数是6101, 6101, 'feature/basic-opened', 6101, 'master' 属性message @存在重复并且未关闭的合并请求: ID6101
- 执行ppmModel模块的checkSameOpenedTest方法，参数是6102, 6102, 'feature/basic-closed', 6102, 'master' 属性result @success
- 执行ppmModel模块的checkSameOpenedTest方法，参数是6101, 6101, 'feature/basic-new', 6101, 'master' 属性result @success
- 执行ppmModel模块的checkSameOpenedTest方法，参数是6101, 6102, 'feature/cross-repo', 6101, 'master' 属性result @success

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

r($ppmModel->checkSameOpenedTest(6101, 6101, 'master', 6101, 'master')) && p('result') && e('fail');
r($ppmModel->checkSameOpenedTest(6101, 6101, 'feature/basic-opened', 6101, 'master')) && p('message') && e('存在重复并且未关闭的合并请求: ID6101');
r($ppmModel->checkSameOpenedTest(6102, 6102, 'feature/basic-closed', 6102, 'master')) && p('result') && e('success');
r($ppmModel->checkSameOpenedTest(6101, 6101, 'feature/basic-new', 6101, 'master')) && p('result') && e('success');
r($ppmModel->checkSameOpenedTest(6101, 6102, 'feature/cross-repo', 6101, 'master')) && p('result') && e('success');