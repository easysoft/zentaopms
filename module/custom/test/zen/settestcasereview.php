#!/usr/bin/env php
<?php

/**

title=测试 customZen::setTestcaseReview();
timeout=0
cid=0

- 执行customTest模块的setTestcaseReviewTest方法，参数是array  @1
- 执行customTest模块的setTestcaseReviewTest方法，参数是array  @1
- 执行customTest模块的setTestcaseReviewTest方法，参数是array  @1
- 执行customTest模块的setTestcaseReviewTest方法，参数是array  @1
- 执行customTest模块的setTestcaseReviewTest方法，参数是array  @1
- 执行customTest模块的setTestcaseReviewTest方法，参数是array  @1
- 执行customTest模块的setTestcaseReviewTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('config')->gen(0);

su('admin');

$customTest = new customZenTest();

r($customTest->setTestcaseReviewTest(array('needReview' => '1', 'forceReview' => array('user1', 'user2'), 'forceNotReview' => array('user3', 'user4')))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '0', 'forceReview' => array('admin', 'test'), 'forceNotReview' => array('user1', 'user2')))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '1', 'forceReview' => array('user1')))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '0', 'forceNotReview' => array('admin')))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '1', 'forceReview' => array(), 'forceNotReview' => array('user1', 'user2', 'user3')))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '0', 'forceReview' => array('user1', 'user2', 'user3', 'user4'), 'forceNotReview' => array()))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '1', 'forceReview' => array('test'), 'forceNotReview' => array('admin')))) && p() && e('1');