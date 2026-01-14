#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen('10');

su('user1');

/**

title=测试 testcaseModel->forceNotReview();
cid=18972

- 测试检查needReview true forceReviewList false forceNotReviewList false 时是否强制审核 @2
- 测试检查needReview true forceReviewList false forceNotReviewList user1,user2 时是否强制审核 @1
- 测试检查needReview true forceReviewList false forceNotReviewList '' 时是否强制审核 @2
- 测试检查needReview true forceReviewList user1,user2 forceNotReviewList false 时是否强制审核 @2
- 测试检查needReview true forceReviewList user1,user2 forceNotReviewList user1,user2 时是否强制审核 @1
- 测试检查needReview true forceReviewList user1,user2 forceNotReviewList '' 时是否强制审核 @2
- 测试检查needReview true forceReviewList '' forceNotReviewList false 时是否强制审核 @2
- 测试检查needReview true forceReviewList '' forceNotReviewList user1,user2 时是否强制审核 @1
- 测试检查needReview true forceReviewList '' forceNotReviewList '' 时是否强制审核 @2
- 测试检查needReview false forceReviewList false forceNotReviewList false 时是否强制审核 @2
- 测试检查needReview false forceReviewList false forceNotReviewList user1,user2 时是否强制审核 @1
- 测试检查needReview false forceReviewList false forceNotReviewList '' 时是否强制审核 @2
- 测试检查needReview false forceReviewList user1,user2 forceNotReviewList false 时是否强制审核 @2
- 测试检查needReview false forceReviewList user1,user2 forceNotReviewList user1,user2 时是否强制审核 @1
- 测试检查needReview false forceReviewList user1,user2 forceNotReviewList '' 时是否强制审核 @2
- 测试检查needReview false forceReviewList '' forceNotReviewList false 时是否强制审核 @2
- 测试检查needReview false forceReviewList '' forceNotReviewList user1,user2 时是否强制审核 @1
- 测试检查needReview false forceReviewList '' forceNotReviewList '' 时是否强制审核 @2

*/

$testcase = new testcaseModelTest();

$needReviewList     = array(true, false);
$forceReviewList    = array(false, 'user1,user2', '');
$forceNotReviewList = array(false, 'user1,user2', '');

r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[0], $forceNotReviewList[0])) && p() && e('2'); // 测试检查needReview true forceReviewList false forceNotReviewList false 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[0], $forceNotReviewList[1])) && p() && e('1'); // 测试检查needReview true forceReviewList false forceNotReviewList user1,user2 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[0], $forceNotReviewList[2])) && p() && e('2'); // 测试检查needReview true forceReviewList false forceNotReviewList '' 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[1], $forceNotReviewList[0])) && p() && e('2'); // 测试检查needReview true forceReviewList user1,user2 forceNotReviewList false 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[1], $forceNotReviewList[1])) && p() && e('1'); // 测试检查needReview true forceReviewList user1,user2 forceNotReviewList user1,user2 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[1], $forceNotReviewList[2])) && p() && e('2'); // 测试检查needReview true forceReviewList user1,user2 forceNotReviewList '' 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[2], $forceNotReviewList[0])) && p() && e('2'); // 测试检查needReview true forceReviewList '' forceNotReviewList false 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[2], $forceNotReviewList[1])) && p() && e('1'); // 测试检查needReview true forceReviewList '' forceNotReviewList user1,user2 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[2], $forceNotReviewList[2])) && p() && e('2'); // 测试检查needReview true forceReviewList '' forceNotReviewList '' 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[0], $forceNotReviewList[0])) && p() && e('2'); // 测试检查needReview false forceReviewList false forceNotReviewList false 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[0], $forceNotReviewList[1])) && p() && e('1'); // 测试检查needReview false forceReviewList false forceNotReviewList user1,user2 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[0], $forceNotReviewList[2])) && p() && e('2'); // 测试检查needReview false forceReviewList false forceNotReviewList '' 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[1], $forceNotReviewList[0])) && p() && e('2'); // 测试检查needReview false forceReviewList user1,user2 forceNotReviewList false 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[1], $forceNotReviewList[1])) && p() && e('1'); // 测试检查needReview false forceReviewList user1,user2 forceNotReviewList user1,user2 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[1], $forceNotReviewList[2])) && p() && e('2'); // 测试检查needReview false forceReviewList user1,user2 forceNotReviewList '' 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[2], $forceNotReviewList[0])) && p() && e('2'); // 测试检查needReview false forceReviewList '' forceNotReviewList false 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[2], $forceNotReviewList[1])) && p() && e('1'); // 测试检查needReview false forceReviewList '' forceNotReviewList user1,user2 时是否强制审核
r($testcase->forceNotReviewTest($needReviewList[0], $forceReviewList[2], $forceNotReviewList[2])) && p() && e('2'); // 测试检查needReview false forceReviewList '' forceNotReviewList '' 时是否强制审核
