#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getReviewerPairs();
cid=0

- 执行storyModel模块的getReviewerPairs方法，参数是1, 2 属性user2 @rejust
- 执行storyModel模块的getReviewerPairs方法，参数是1, 1 属性admin @~~
- 执行storyModel模块的getReviewerPairs方法，参数是2, 1 属性admin @~~
- 执行storyModel模块的getReviewerPairs方法，参数是2, 2 属性user1 @pass

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$storyView = zdTable('storyreview');
$storyView->story->range('1-5');
$storyView->reviewer->range('admin,user1,user2');
$storyView->version->range('1,2');
$storyView->result->range('``,pass,rejust');
$storyView->gen(10);

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->getReviewerPairs(1, 2)) && p('user2') && e('rejust');
r($storyModel->getReviewerPairs(1, 1)) && p('admin') && e('~~');
r($storyModel->getReviewerPairs(2, 1)) && p('admin') && e('~~');
r($storyModel->getReviewerPairs(2, 2)) && p('user1') && e('pass');
