#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getReviewListByCreator();
timeout=0
cid=0

- 步骤1：查询当前用户创建的全部评审请求 @5
- 步骤2：查询当前用户创建的开放中的评审请求 @3
- 步骤3：查询当前用户创建的已合并的评审请求 @1
- 步骤4：查询当前用户创建的已关闭的评审请求 @1
- 步骤5：查询其他用户创建的评审请求 @2
- 步骤6：我创建的评审请求按提交时间倒序排列第8条的title属性 @Review 8
- 步骤7：按分页参数查询第一页数据 @2
- 步骤8：按分页参数查询第二页数据 @2
- 步骤9：评审请求类型按照流程标识映射第2条的type属性 @pr
- 步骤10：流程标识为0时映射为合并请求第1条的type属性 @mr
- 步骤11：已删除的评审请求不在列表中 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

/* 评审请求数据：1、2、3、4、8号由admin创建，9号已删除。 */
$ppm = zenData('ops_ppm');
$ppm->id->range('1-9');
$ppm->title->range('Review 1,Review 2,Review 3,Review 4,Review 5,Review 6,Review 7,Review 8,Review 9');
$ppm->repoID->range('1');
$ppm->sourceRepoID->range('1');
$ppm->sourceBranch->range('feature/branch-1,feature/branch-2,feature/branch-3,feature/branch-4,feature/branch-5,feature/branch-6,feature/branch-7,feature/branch-8,feature/branch-9');
$ppm->targetRepoID->range('1');
$ppm->targetBranch->range('master');
$ppm->flow->range('0,1,0,0,0,0,0,0,0');
$ppm->status->range('opened,merged,closed,opened,opened,merged,closed,opened,opened');
$ppm->createdBy->range('admin,admin,admin,admin,user1,user1,user2,admin,admin');
$ppm->createdDate->range('`2026-01-01 09:00:00`,`2026-01-02 09:00:00`,`2026-01-03 09:00:00`,`2026-01-04 09:00:00`,`2026-01-05 09:00:00`,`2026-01-06 09:00:00`,`2026-01-07 09:00:00`,`2026-01-08 09:00:00`,`2026-01-09 09:00:00`');
$ppm->deleted->range('0,0,0,0,0,0,0,0,1');
$ppm->reviewStatus->range('pending');
$ppm->gen(9);

su('admin');

global $tester;
$tester->app->loadClass('pager', true);

$ppmModel = new ppmModelTest();

r(count($ppmModel->getReviewListByCreatorTest('all', 'admin')))    && p() && e('5');        // 步骤1：查询当前用户创建的全部评审请求
r(count($ppmModel->getReviewListByCreatorTest('opened', 'admin'))) && p() && e('3');        // 步骤2：查询当前用户创建的开放中的评审请求
r(count($ppmModel->getReviewListByCreatorTest('merged', 'admin'))) && p() && e('1');        // 步骤3：查询当前用户创建的已合并的评审请求
r(count($ppmModel->getReviewListByCreatorTest('closed', 'admin'))) && p() && e('1');        // 步骤4：查询当前用户创建的已关闭的评审请求
r(count($ppmModel->getReviewListByCreatorTest('all', 'user1')))    && p() && e('2');        // 步骤5：查询其他用户创建的评审请求
r($ppmModel->getReviewListByCreatorTest('all', 'admin'))           && p('8:title') && e('Review 8');    // 步骤6：我创建的评审请求按提交时间倒序排列
r(count($ppmModel->getReviewListByCreatorTest('all', 'admin', 'createdDate_desc', pager::init(0, 2, 1)))) && p() && e('2'); // 步骤7：按分页参数查询第一页数据
r(count($ppmModel->getReviewListByCreatorTest('all', 'admin', 'createdDate_desc', pager::init(0, 2, 2)))) && p() && e('2'); // 步骤8：按分页参数查询第二页数据
r($ppmModel->getReviewListByCreatorTest('all', 'admin'))           && p('2:type')  && e('pr');           // 步骤9：评审请求类型按照流程标识映射
r($ppmModel->getReviewListByCreatorTest('all', 'admin'))           && p('1:type')  && e('mr');           // 步骤10：流程标识为0时映射为合并请求
r(!isset($ppmModel->getReviewListByCreatorTest('all', 'admin')[9])) && p() && e('1');       // 步骤11：已删除的评审请求不在列表中
