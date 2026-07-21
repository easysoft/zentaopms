#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getList();
timeout=0
cid=0

- 执行ppmModel模块的getListTest方法  @4
- 执行ppmModel模块的getListTest方法，参数是'status', 'opened'  @3
- 执行ppmModel模块的getListTest方法，参数是'status', 'closed' 第6102条的title属性 @Closed PPM 6102
- 执行ppmModel模块的getListTest方法，参数是'creator', 'user1'
 - 第6102条的title属性 @Closed PPM 6102
 - 第6102条的title属性 @Closed PPM 6102
- 执行ppmModel模块的getListTest方法，参数是'all', 'all', 'id_desc', array  @2
- 执行ppmModel模块的getListTest方法，参数是'creator', 'all'  @4
- 执行ppmModel模块的getListTest方法，参数是'status', 'all'  @4
- 执行ppmModel模块的getListTest方法，参数是'creator', 'nonexistent'  @0
- 执行ppmModel模块的getListTest方法，参数是'all', 'all', 'id_asc' 第6101条的status属性 @opened
- 执行ppmModel模块的getListTest方法，参数是'all', 'all', 'id_desc', array  @2

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
$ppm->id->range('6101-6104');
$ppm->title->range('Opened PPM 6101,Closed PPM 6102,Review PPM 6103,Extra PPM 6104');
$ppm->repoID->range('6101,6102,6101,6102');
$ppm->sourceRepoID->range('6101,6102,6101,6102');
$ppm->sourceBranch->range('feature/basic-opened,feature/basic-closed,feature/basic-review,feature/extra');
$ppm->targetRepoID->range('6101,6102,6101,6102');
$ppm->targetBranch->range('master{4}');
$ppm->status->range('opened,closed,opened,opened');
$ppm->createdBy->range('admin,user1,admin,user1');
$ppm->reviewers->range('admin,user1,admin,user1');
$ppm->reviewStatus->range('pending,approved,rejected,pending');
$ppm->gen(4);

su('admin');

$ppmModel = new ppmModelTest();

r(count($ppmModel->getListTest())) && p() && e('4');
r(count($ppmModel->getListTest('status', 'opened'))) && p() && e('3');
r($ppmModel->getListTest('status', 'closed')) && p('6102:title') && e('Closed PPM 6102');
r($ppmModel->getListTest('creator', 'user1')) && p('6102:title,title') && e('Closed PPM 6102,Closed PPM 6102');
r(count($ppmModel->getListTest('all', 'all', 'id_desc', array(), 6101))) && p() && e('2');
r(count($ppmModel->getListTest('creator', 'all'))) && p() && e('4');
r(count($ppmModel->getListTest('status', 'all'))) && p() && e('4');
r(count($ppmModel->getListTest('creator', 'nonexistent'))) && p() && e('0');
r($ppmModel->getListTest('all', 'all', 'id_asc')) && p('6101:status') && e('opened');
su('user1');
r(count($ppmModel->getListTest('all', 'all', 'id_desc', array(6102 => 6102)))) && p() && e('2');