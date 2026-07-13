#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::addReviewers();
timeout=0
cid=0

- 执行ppmModel模块的addReviewersTest方法，参数是$ppm, array  @1
- 执行$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPMREVIEWERS)->where('requestID')->eq(6301)->andWhere('account')->eq('user2')->fetch('count(*)' @1
- 执行ppmModel模块的addReviewersTest方法，参数是$ppm, array  @1
- 执行$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPMREVIEWERS)->where('requestID')->eq(6301)->fetch('count(*)' @4
- 执行ppmModel模块的addReviewersTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(4);
zenData('action')->gen(0);

$reviewers = zenData('ops_request_reviewers')->loadYaml('ops_request_reviewers', false, 2);
$reviewers->id->range('1-2');
$reviewers->requestID->range('6301{2}');
$reviewers->repoID->range('6301{2}');
$reviewers->account->range('admin,user1');
$reviewers->decision->range('pending,approved');
$reviewers->gen(2);

su('admin');

$ppmModel = new ppmModelTest();
$ppm      = (object)array('id' => 6301, 'repoID' => 6301, 'sourceSHA' => '1111111111111111111111111111111111111111');

r($ppmModel->addReviewersTest($ppm, array('user2'))) && p() && e('1');
r((int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPMREVIEWERS)->where('requestID')->eq(6301)->andWhere('account')->eq('user2')->fetch('count(*)')) && p() && e('1');
r($ppmModel->addReviewersTest($ppm, array('user3'))) && p() && e('1');
r((int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_PPMREVIEWERS)->where('requestID')->eq(6301)->fetch('count(*)')) && p() && e('4');
r($ppmModel->addReviewersTest((object)array('id' => 0, 'repoID' => 6301, 'sourceSHA' => '1'), array('user2'))) && p() && e('0');