#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getReviewListByAccount();
timeout=0
cid=0

- 步骤1：查询指派给当前用户的全部评审请求 @7
- 步骤2：查询待我评审的评审请求 @3
- 步骤3：查询我已评审的评审请求 @2
- 步骤4：待我评审的评审请求按提交时间倒序排列第7条的title属性 @Review 7
- 步骤5：按分页参数查询第一页数据 @2
- 步骤6：按分页参数查询第二页数据 @2
- 步骤7：评审请求类型按照流程标识映射第2条的type属性 @pr
- 步骤8：查询其他用户待评审的评审请求 @1
- 步骤9：流程标识为0时映射为合并请求第1条的type属性 @mr
- 步骤10：审批已通过的评审请求仍查询在全部列表中第8条的title属性 @Review 8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

/* 评审请求数据：3号已关闭，2号走自定义评审流程，7号只在当前节点评审人中，8号审批已通过。 */
$ppm = zenData('ops_ppm');
$ppm->id->range('1-8');
$ppm->title->range('Review 1,Review 2,Review 3,Review 4,Review 5,Review 6,Review 7,Review 8');
$ppm->repoID->range('1');
$ppm->sourceRepoID->range('1');
$ppm->sourceBranch->range('feature/branch-1,feature/branch-2,feature/branch-3,feature/branch-4,feature/branch-5,feature/branch-6,feature/branch-7,feature/branch-8');
$ppm->targetRepoID->range('1');
$ppm->targetBranch->range('master');
$ppm->flow->range('0,1,0,0,0,0,0,0');
$ppm->status->range('opened,opened,closed,opened,opened,opened,opened,opened');
$ppm->reviewers->range('');
$ppm->createdBy->range('user1,user2,user3,user4,user5,user1,user2,user3');
$ppm->createdDate->range('`2026-01-01 09:00:00`,`2026-01-02 09:00:00`,`2026-01-03 09:00:00`,`2026-01-04 09:00:00`,`2026-01-05 09:00:00`,`2026-01-06 09:00:00`,`2026-01-07 09:00:00`,`2026-01-08 09:00:00`');
$ppm->reviewStatus->range('pending');
$ppm->gen(8);

/* 7号只记录在当前节点评审人中，8号审批已通过。 */
$tester->dao->update(TABLE_PPM)->set('reviewers')->eq('admin')->where('id')->in('7,8')->exec();
$tester->dao->update(TABLE_PPM)->set('reviewStatus')->eq('')->where('id')->eq('7')->exec();
$tester->dao->update(TABLE_PPM)->set('reviewStatus')->eq('pass')->where('id')->eq('8')->exec();

/* 评审人数据：6号评审人不是当前用户，3号评审请求已关闭，7号没有评审人记录。 */
$reviewers = zenData('ops_request_reviewers');
$reviewers->id->range('1-7');
$reviewers->requestID->range('1,2,3,4,5,6,8');
$reviewers->repoID->range('1');
$reviewers->decision->range('pending,approved,pending,rejected,pending,pending,pending');
$reviewers->account->range('admin,admin,admin,admin,admin,user1,admin');
$reviewers->createdBy->range('admin');
$reviewers->createdDate->range('`2026-01-01 09:00:00`');
$reviewers->gen(7);

su('admin');

global $tester;
$tester->app->loadClass('pager', true);

$ppmModel = new ppmModelTest();

r(count($ppmModel->getReviewListByAccountTest('all', 'admin')))     && p() && e('7');         // 步骤1：查询指派给当前用户的全部评审请求
r(count($ppmModel->getReviewListByAccountTest('pending', 'admin'))) && p() && e('3');         // 步骤2：查询待我评审的评审请求
r(count($ppmModel->getReviewListByAccountTest('reviewed', 'admin'))) && p() && e('2');        // 步骤3：查询我已评审的评审请求
r($ppmModel->getReviewListByAccountTest('pending', 'admin'))        && p('7:title') && e('Review 7');    // 步骤4：待我评审的评审请求按提交时间倒序排列
r(count($ppmModel->getReviewListByAccountTest('all', 'admin', 'createdDate_desc', pager::init(0, 2, 1)))) && p() && e('2'); // 步骤5：按分页参数查询第一页数据
r(count($ppmModel->getReviewListByAccountTest('all', 'admin', 'createdDate_desc', pager::init(0, 2, 2)))) && p() && e('2'); // 步骤6：按分页参数查询第二页数据
r($ppmModel->getReviewListByAccountTest('all', 'admin'))            && p('2:type')  && e('pr');        // 步骤7：评审请求类型按照流程标识映射
r(count($ppmModel->getReviewListByAccountTest('pending', 'user1'))) && p() && e('1');         // 步骤8：查询其他用户待评审的评审请求
r($ppmModel->getReviewListByAccountTest('all', 'admin'))            && p('1:type')  && e('mr');        // 步骤9：流程标识为0时映射为合并请求
r($ppmModel->getReviewListByAccountTest('all', 'admin'))            && p('8:title') && e('Review 8');  // 步骤10：审批已通过的评审请求仍查询在全部列表中