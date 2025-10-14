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

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('config')->gen(0);

su('admin');

$customTest = new customTest();

r($customTest->setTestcaseReviewTest(array('needReview' => '1', 'forceNotReview' => array('user1', 'user2')))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '0', 'forceReview' => array('admin', 'pm')))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '1'))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '0'))) && p() && e('1');
r($customTest->setTestcaseReviewTest(array('needReview' => '1', 'forceNotReview' => array('tester1', 'tester2')))) && p() && e('1');