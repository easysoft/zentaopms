#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::deleteReviewer();
timeout=0
cid=0

- 执行ppmModel模块的deleteReviewerTest方法，参数是6301, 'user2'  @1
- 执行$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPMREVIEWERS)->where('requestID')->eq(6301)->andWhere('account')->eq('user2')->fetch('count(*)' @0
- 执行ppmModel模块的deleteReviewerTest方法，参数是6301, ''  @0
- 执行ppmModel模块的deleteReviewerTest方法，参数是0, 'user1'  @0
- 执行ppmModel模块的deleteReviewerTest方法，参数是6301, 'admin'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(4);
zenData('action')->gen(0);

$reviewers = zenData('ops_request_reviewers')->loadYaml('ops_request_reviewers', false, 2);
$reviewers->id->range('1-3');
$reviewers->requestID->range('6301{3}');
$reviewers->repoID->range('6301{3}');
$reviewers->account->range('admin,user1,user2');
$reviewers->decision->range('pending,approved,pending');
$reviewers->gen(3);

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->deleteReviewerTest(6301, 'user2')) && p() && e('1');
r((int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPMREVIEWERS)->where('requestID')->eq(6301)->andWhere('account')->eq('user2')->fetch('count(*)')) && p() && e('0');
r($ppmModel->deleteReviewerTest(6301, '')) && p() && e('0');
r($ppmModel->deleteReviewerTest(0, 'user1')) && p() && e('0');
r($ppmModel->deleteReviewerTest(6301, 'admin')) && p() && e('1');