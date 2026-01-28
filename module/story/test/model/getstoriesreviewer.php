#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getStoriesReviewer();
timeout=0
cid=18559

- 执行storyTest模块的getStoriesReviewerTest方法，参数是1  @A:管理员
- 执行storyTest模块的getStoriesReviewerTest方法，参数是2  @U:用户1
- 执行storyTest模块的getStoriesReviewerTest方法，参数是3  @U:用户2
- 执行storyTest模块的getStoriesReviewerTest方法，参数是4  @A:管理员
- 执行storyTest模块的getStoriesReviewerTest方法，参数是5  @U:用户1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->acl->range('open{2},private{2},custom{1}');
$product->reviewer->range('admin,user1,user2', 'user1,user2,user3', 'admin', '', '');
$product->groups->range('1,2,3', '2,3', '1', '', '');
$product->whitelist->range('admin,user1', 'user2,user3', '', '', '');
$product->deleted->range('0{5}');
$product->gen(5);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{10}');
$user->gen(10);

su('admin');

$storyTest = new storyModelTest();

r($storyTest->getStoriesReviewerTest(1)) && p() && e('A:管理员');
r($storyTest->getStoriesReviewerTest(2)) && p() && e('U:用户1');
r($storyTest->getStoriesReviewerTest(3)) && p() && e('U:用户2');
r($storyTest->getStoriesReviewerTest(4)) && p() && e('A:管理员');
r($storyTest->getStoriesReviewerTest(5)) && p() && e('U:用户1');