#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printReviewCreatedByMeBlock();
timeout=0
cid=0

- 步骤1：验证全部标签返回我创建的全部评审请求 @5
- 步骤2：验证开放中标签只返回开放中的评审请求 @3
- 步骤3：验证已合并标签只返回已合并的评审请求 @1
- 步骤4：验证已关闭标签只返回已关闭的评审请求 @1
- 步骤5：验证无效的筛选标签默认为全部 @all
- 步骤6：验证列表按照提交时间倒序排列第8条的title属性 @Review 8
- 步骤7：验证列表条数由区块数量参数决定 @2
- 步骤8：验证评审请求类型按照流程标识映射第2条的type属性 @pr
- 步骤9：验证流程标识为0的评审请求类型为合并请求第1条的type属性 @mr
- 步骤10：验证已删除的评审请求不在列表中 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $config, $tester;

/* 模拟GitFox独立版本。 */
$config->edition = 'gitfox';
$config->vision  = 'gitfox';

zendata('user')->gen(5);

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

/* 加载GitFox版本的区块语言项。 */
$tester->app->loadLang('block');
$extLangPath = $tester->app->getModuleExtPath('block', 'lang');
include $extLangPath['common'] . 'zh-cn/gitfox.php';

$blockTest = new blockZenTest();

$block = new stdclass();
$block->id     = 1;
$block->params = new stdclass();
$block->params->count = 20;

$allView    = $blockTest->printReviewCreatedByMeBlockTest($block, array('browseType' => 'all'));
$openedView = $blockTest->printReviewCreatedByMeBlockTest($block, array('browseType' => 'opened'));
$mergedView = $blockTest->printReviewCreatedByMeBlockTest($block, array('browseType' => 'merged'));
$closedView = $blockTest->printReviewCreatedByMeBlockTest($block, array('browseType' => 'closed'));

r(count($allView->reviews))    && p() && e('5'); // 步骤1：验证全部标签返回我创建的全部评审请求
r(count($openedView->reviews)) && p() && e('3'); // 步骤2：验证开放中标签只返回开放中的评审请求
r(count($mergedView->reviews)) && p() && e('1'); // 步骤3：验证已合并标签只返回已合并的评审请求
r(count($closedView->reviews)) && p() && e('1'); // 步骤4：验证已关闭标签只返回已关闭的评审请求

$unknownView = $blockTest->printReviewCreatedByMeBlockTest($block, array('browseType' => 'unknown'));
r($unknownView->browseType) && p() && e('all'); // 步骤5：验证无效的筛选标签默认为全部

r($allView->reviews) && p('8:title') && e('Review 8'); // 步骤6：验证列表按照提交时间倒序排列

$block->params->count = 2;
$limitedView = $blockTest->printReviewCreatedByMeBlockTest($block, array('browseType' => 'all'));
r(count($limitedView->reviews)) && p() && e('2'); // 步骤7：验证列表条数由区块数量参数决定

r($allView->reviews) && p('2:type') && e('pr'); // 步骤8：验证评审请求类型按照流程标识映射
r($allView->reviews) && p('1:type') && e('mr'); // 步骤9：验证流程标识为0的评审请求类型为合并请求

r(!isset($allView->reviews[9])) && p() && e('1'); // 步骤10：验证已删除的评审请求不在列表中
