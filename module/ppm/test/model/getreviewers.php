#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getReviewers();
timeout=0
cid=0

- 执行$reviewerList @2
- 执行$reviewerList
 - 第admin条的decision属性 @pending
 - 第admin条的requestID属性 @6301
- 执行$reviewerList
 - 第user1条的decision属性 @approved
 - 第user1条的requestID属性 @6301
- 执行ppmModel模块的getReviewersTest方法，参数是9999  @0
- 执行$reviewerList @admin,user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(4);

$reviewers = zenData('ops_request_reviewers')->loadYaml('ops_request_reviewers', false, 2);
$reviewers->id->range('1-2');
$reviewers->requestID->range('6301{2}');
$reviewers->repoID->range('6301{2}');
$reviewers->account->range('admin,user1');
$reviewers->decision->range('pending,approved');
$reviewers->gen(2);

su('admin');

$ppmModel = new ppmModelTest();
$reviewerList = $ppmModel->getReviewersTest(6301);

r(count($reviewerList)) && p() && e('2');
r($reviewerList) && p('admin:decision,requestID') && e('pending,6301');
r($reviewerList) && p('user1:decision,requestID') && e('approved,6301');
r($ppmModel->getReviewersTest(9999)) && p() && e('0');
r(implode(',', array_keys($reviewerList))) && p() && e('admin,user1');