#!/usr/bin/env php
<?php

/**

title=测试 myModel::getMyReviewStatistics();
timeout=0
cid=0

- 步骤1：没有评审数据时我发起的评审数为0 @0
- 步骤2：没有评审数据时待我解决的问题数为0 @0
- 步骤3：我发起的未关闭评审计入统计 @1
- 步骤4：待我解决的问题计入统计 @2
- 步骤5：其他人发起的评审不计入统计 @1
- 步骤6：已删除的问题不计入统计 @1
- 步骤7：已解决的问题不计入统计 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('ops_ppm')->gen(0);
zenData('ops_scan_issues')->gen(0);

$my = new myModelTest();

r($my->getMyReviewStatisticsFieldTest('createdReviews')) && p() && e('0'); // 步骤1：没有评审数据时我发起的评审数为0
r($my->getMyReviewStatisticsFieldTest('pendingIssues'))  && p() && e('0'); // 步骤2：没有评审数据时待我解决的问题数为0

/* 评审数据：1号未关闭、2号已关闭、3号已删除都由admin发起，4号由user1发起。*/
$ppm = zenData('ops_ppm');
$ppm->id->range('1-4');
$ppm->title->range('review1,review2,review3,review4');
$ppm->status->range('opened,closed,opened,opened');
$ppm->createdBy->range('admin,admin,admin,user1');
$ppm->deleted->range('0,0,1,0');
$ppm->createdDate->range('`2026-01-01 00:00:00`');
$ppm->gen(4);

r($my->getMyReviewStatisticsFieldTest('createdReviews')) && p() && e('1'); // 步骤3：我发起的未关闭评审计入统计

/* 问题数据：1号问题待处理；2号问题所属评审已关闭仍属于我发起的评审；3号问题所属评审已删除；4号问题不属于我发起的评审。*/
$issue = zenData('ops_scan_issues');
$issue->id->range('1-4');
$issue->issueKey->range('issuekey-001,issuekey-002,issuekey-003,issuekey-004');
$issue->ppmID->range('1,2,3,4');
$issue->status->range('todo,solving,wait,todo');
$issue->deleted->range('0,0,0,0');
$issue->gen(4);

r($my->getMyReviewStatisticsFieldTest('pendingIssues')) && p() && e('2'); // 步骤4：待我解决的问题计入统计

$tester->app->user->account = 'user1';
r($my->getMyReviewStatisticsFieldTest('createdReviews')) && p() && e('1'); // 步骤5：其他人发起的评审不计入统计

$tester->app->user->account = 'admin';
$tester->dao->update(TABLE_SCANISSUE)->set('deleted')->eq(1)->where('id')->eq(1)->exec();
r($my->getMyReviewStatisticsFieldTest('pendingIssues')) && p() && e('1'); // 步骤6：已删除的问题不计入统计

$tester->dao->update(TABLE_SCANISSUE)->set('status')->eq('solved')->where('id')->eq(2)->exec();
r($my->getMyReviewStatisticsFieldTest('pendingIssues')) && p() && e('0'); // 步骤7：已解决的问题不计入统计
