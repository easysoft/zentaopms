#!/usr/bin/env php
<?php

/**

title=测试 customZen::setStoryReview();
timeout=0
cid=15938

- 执行customTest模块的setStoryReviewTest方法，参数是'story', array  @1
- 执行customTest模块的setStoryReviewTest方法，参数是'requirement', array  @1
- 执行customTest模块的setStoryReviewTest方法，参数是'demand', array  @1
- 执行customTest模块的setStoryReviewTest方法，参数是'epic', array  @1
- 执行customTest模块的setStoryReviewTest方法，参数是'story', array  @1
- 执行customTest模块的setStoryReviewTest方法，参数是'requirement', array  @1
- 执行customTest模块的setStoryReviewTest方法，参数是'demand', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('config')->gen(0);

su('admin');

$customTest = new customZenTest();

r($customTest->setStoryReviewTest('story', array('needReview' => '1', 'forceReview' => array('user1', 'user2'), 'forceNotReview' => array(), 'forceReviewRoles' => array('dev', 'qa'), 'forceNotReviewRoles' => array(), 'forceReviewDepts' => array('1', '2'), 'forceNotReviewDepts' => array()))) && p() && e('1');
r($customTest->setStoryReviewTest('requirement', array('needReview' => '0', 'forceReview' => array(), 'forceNotReview' => array('admin', 'test'), 'forceReviewRoles' => array(), 'forceNotReviewRoles' => array('pm'), 'forceReviewDepts' => array(), 'forceNotReviewDepts' => array('3')))) && p() && e('1');
r($customTest->setStoryReviewTest('demand', array('needReview' => '1', 'forceReview' => array('user1', 'user2', 'user3'), 'forceNotReview' => array(), 'forceReviewRoles' => array(), 'forceNotReviewRoles' => array(), 'forceReviewDepts' => array(), 'forceNotReviewDepts' => array()))) && p() && e('1');
r($customTest->setStoryReviewTest('epic', array('needReview' => '0', 'forceReview' => array(), 'forceNotReview' => array('user1', 'admin'), 'forceReviewRoles' => array(), 'forceNotReviewRoles' => array('dev', 'qa', 'pm'), 'forceReviewDepts' => array(), 'forceNotReviewDepts' => array('1', '2', '3')))) && p() && e('1');
r($customTest->setStoryReviewTest('story', array('needReview' => '0', 'forceReview' => array('test'), 'forceNotReview' => array(), 'forceReviewRoles' => array('dev'), 'forceNotReviewRoles' => array(), 'forceReviewDepts' => array('5'), 'forceNotReviewDepts' => array()))) && p() && e('1');
r($customTest->setStoryReviewTest('requirement', array('needReview' => '1', 'forceReview' => array(), 'forceNotReview' => array('test'), 'forceReviewRoles' => array(), 'forceNotReviewRoles' => array('pm'), 'forceReviewDepts' => array(), 'forceNotReviewDepts' => array('6')))) && p() && e('1');
r($customTest->setStoryReviewTest('demand', array('needReview' => '1', 'forceReview' => array(), 'forceNotReview' => array(), 'forceReviewRoles' => array(), 'forceNotReviewRoles' => array(), 'forceReviewDepts' => array(), 'forceNotReviewDepts' => array()))) && p() && e('1');