#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::review();
timeout=0
cid=0

- 执行ppmModel模块的reviewTest方法，参数是6301,   @1
- 执行ppmModel模块的getReviewersTest方法，参数是6301
 - 第admin条的decision属性 @approved
 - 第admin条的opinion属性 @ok
- 执行ppmModel模块的reviewTest方法，参数是6301,   @1
- 执行ppmModel模块的getReviewersTest方法，参数是6301
 - 第user1条的decision属性 @rejected
 - 第user1条的opinion属性 @no
- 执行ppmModel模块的reviewTest方法，参数是6301,   @1

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

r($ppmModel->reviewTest(6301, (object)array('decision' => 'approved', 'opinion' => 'ok'))) && p() && e('1');
r($ppmModel->getReviewersTest(6301)) && p('admin:decision,opinion') && e('approved,ok');
r($ppmModel->reviewTest(6301, (object)array('decision' => 'rejected', 'opinion' => 'no'), 'user1')) && p() && e('1');
r($ppmModel->getReviewersTest(6301)) && p('user1:decision,opinion') && e('rejected,no');
r($ppmModel->reviewTest(6301, (object)array('decision' => 'approved', 'opinion' => 'ghost'), 'ghost')) && p() && e('1');