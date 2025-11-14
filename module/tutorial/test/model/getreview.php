#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getReview();
timeout=0
cid=19467

- 执行tutorialTest模块的getReviewTest方法 属性id @1
- 执行tutorialTest模块的getReviewTest方法 属性project @2
- 执行tutorialTest模块的getReviewTest方法 属性title @Test Review
- 执行tutorialTest模块的getReviewTest方法 属性status @pass
- 执行tutorialTest模块的getReviewTest方法 属性category @PP

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r($tutorialTest->getReviewTest()) && p('id') && e('1');
r($tutorialTest->getReviewTest()) && p('project') && e('2');
r($tutorialTest->getReviewTest()) && p('title') && e('Test Review');
r($tutorialTest->getReviewTest()) && p('status') && e('pass');
r($tutorialTest->getReviewTest()) && p('category') && e('PP');