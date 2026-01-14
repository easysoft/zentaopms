#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getReviewerPairs();
timeout=0
cid=18553

- 执行storyTest模块的getReviewerPairsTest方法，参数是1, 1 属性admin @pass
- 执行storyTest模块的getReviewerPairsTest方法，参数是1, 1 属性user1 @reject
- 执行storyTest模块的getReviewerPairsTest方法，参数是1, 1 属性user2 @~~
- 执行storyTest模块的getReviewerPairsTest方法，参数是2, 1 属性admin @pass
- 执行storyTest模块的getReviewerPairsTest方法，参数是2, 1 属性user1 @clarify
- 执行storyTest模块的getReviewerPairsTest方法，参数是999, 1  @0
- 执行storyTest模块的getReviewerPairsTest方法，参数是3, 2 属性admin @pass

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$storyReview = zenData('storyreview');
$storyReview->story->range('1,1,1,2,2,3');
$storyReview->reviewer->range('admin,user1,user2,admin,user1,admin');
$storyReview->version->range('1,1,1,1,1,2');
$storyReview->result->range('pass,reject,``,pass,clarify,pass');
$storyReview->gen(6);

su('admin');

$storyTest = new storyModelTest();

r($storyTest->getReviewerPairsTest(1, 1)) && p('admin') && e('pass');
r($storyTest->getReviewerPairsTest(1, 1)) && p('user1') && e('reject');
r($storyTest->getReviewerPairsTest(1, 1)) && p('user2') && e('~~');
r($storyTest->getReviewerPairsTest(2, 1)) && p('admin') && e('pass');
r($storyTest->getReviewerPairsTest(2, 1)) && p('user1') && e('clarify');
r($storyTest->getReviewerPairsTest(999, 1)) && p() && e('0');
r($storyTest->getReviewerPairsTest(3, 2)) && p('admin') && e('pass');