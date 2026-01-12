#!/usr/bin/env php
<?php
/**

title=测试 mrModel::getReviewers();
timeout=0
cid=0

- 获取MR 1的审批人
 - 第admin条的id属性 @1
 - 第admin条的account属性 @admin
 - 第admin条的decision属性 @pending
 - 第admin条的requestID属性 @1
 - 第admin条的opinion属性 @opinion1
- 获取MR 0的审批人 @0
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('ops_request_reviewers')->gen(10);
su('admin');

$mrModel = $tester->loadModel('mr');

r($mrModel->getReviewers(1)) && p('admin:id,account,decision,requestID,opinion') && e('1,admin,pending,1,opinion1'); // 获取MR 1的审批人
r($mrModel->getReviewers(0)) && p()                                              && e('0');                          // 获取MR 0的审批人
