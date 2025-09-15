#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getProductReviewers();
timeout=0
cid=0

- 步骤1：正常产品有reviewer设置 @3
- 步骤2：产品没有reviewer但ACL为open @8
- 步骤3：产品没有reviewer且ACL为private @1
- 步骤4：产品没有reviewer且ACL为custom @3
- 步骤5：不存在的产品ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 直接修改现有产品数据的审核员和访问控制设置
global $tester;
$tester->dao->update(TABLE_PRODUCT)->set('reviewer')->eq('admin,user1,user2')->set('acl')->eq('open')->where('id')->eq(1)->exec();
$tester->dao->update(TABLE_PRODUCT)->set('reviewer')->eq('')->set('acl')->eq('open')->where('id')->eq(2)->exec();
$tester->dao->update(TABLE_PRODUCT)->set('reviewer')->eq('admin')->set('acl')->eq('private')->where('id')->eq(3)->exec();
$tester->dao->update(TABLE_PRODUCT)->set('reviewer')->eq('')->set('acl')->eq('custom')->where('id')->eq(4)->exec();

su('admin');

$storyTest = new storyTest();

r($storyTest->getProductReviewersTest(1, array())) && p() && e('3'); // 步骤1：正常产品有reviewer设置
r($storyTest->getProductReviewersTest(2, array())) && p() && e('8'); // 步骤2：产品没有reviewer但ACL为open
r($storyTest->getProductReviewersTest(3, array())) && p() && e('1'); // 步骤3：产品没有reviewer且ACL为private
r($storyTest->getProductReviewersTest(4, array())) && p() && e('3'); // 步骤4：产品没有reviewer且ACL为custom
r($storyTest->getProductReviewersTest(999, array())) && p() && e('0'); // 步骤5：不存在的产品ID