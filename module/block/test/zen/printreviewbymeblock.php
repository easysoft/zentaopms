#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printReviewByMeBlock();
timeout=0
cid=0

- 步骤1：验证全部标签返回待我评审的全部评审请求 @7
- 步骤2：验证待评审标签只返回未评审的评审请求 @3
- 步骤3：验证已评审标签只返回已评审的评审请求 @2
- 步骤4：验证无效的筛选标签默认为全部 @all
- 步骤5：验证列表按照提交时间倒序排列第8条的title属性 @Review 8
- 步骤6：验证列表条数由区块数量参数决定 @2
- 步骤7：验证评审请求类型按照流程标识映射第2条的type属性 @pr
- 步骤8：验证流程标识为0的评审请求类型为合并请求第1条的type属性 @mr
- 步骤9：验证待评审包含只记录在当前节点评审人中的请求第7条的title属性 @Review 7
- 步骤10：验证审批已通过的请求在全部列表中而不在待评审列表中 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $config, $tester;

/* 模拟GitFox独立版本。 */
$config->edition = 'gitfox';
$config->vision  = 'gitfox';

zendata('user')->gen(5);

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

/* 加载GitFox版本的区块语言项。 */
$tester->app->loadLang('block');
$extLangPath = $tester->app->getModuleExtPath('block', 'lang');
include $extLangPath['common'] . 'zh-cn/gitfox.php';

$blockTest = new blockZenTest();

$block = new stdclass();
$block->id     = 1;
$block->params = new stdclass();
$block->params->count = 20;

$allView = $blockTest->printReviewByMeBlockTest($block, array('browseType' => 'all'));
r(count($allView->reviews)) && p() && e('7'); // 步骤1：验证全部标签返回待我评审的全部评审请求

$pendingView = $blockTest->printReviewByMeBlockTest($block, array('browseType' => 'pending'));
r(count($pendingView->reviews)) && p() && e('3'); // 步骤2：验证待评审标签只返回未评审的评审请求

$reviewedView = $blockTest->printReviewByMeBlockTest($block, array('browseType' => 'reviewed'));
r(count($reviewedView->reviews)) && p() && e('2'); // 步骤3：验证已评审标签只返回已评审的评审请求

$unknownView = $blockTest->printReviewByMeBlockTest($block, array('browseType' => 'unknown'));
r($unknownView->browseType) && p() && e('all'); // 步骤4：验证无效的筛选标签默认为全部

r($allView->reviews) && p('8:title') && e('Review 8'); // 步骤5：验证列表按照提交时间倒序排列

$block->params->count = 2;
$limitedView = $blockTest->printReviewByMeBlockTest($block, array('browseType' => 'all'));
r(count($limitedView->reviews)) && p() && e('2'); // 步骤6：验证列表条数由区块数量参数决定

r($allView->reviews) && p('2:type') && e('pr'); // 步骤7：验证评审请求类型按照流程标识映射
r($allView->reviews) && p('1:type') && e('mr'); // 步骤8：验证流程标识为0的评审请求类型为合并请求

r($pendingView->reviews) && p('7:title') && e('Review 7'); // 步骤9：验证待评审包含只记录在当前节点评审人中的请求
r(isset($allView->reviews[8]) && !isset($pendingView->reviews[8])) && p() && e('1'); // 步骤10：验证审批已通过的请求在全部列表中而不在待评审列表中