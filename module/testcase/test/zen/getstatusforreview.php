#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getStatusForReview();
timeout=0
cid=19097

- 执行testcaseTest模块的getStatusForReviewTest方法，参数是$case1, 'pass'  @normal
- 执行testcaseTest模块的getStatusForReviewTest方法，参数是$case2, 'pass'  @normal
- 执行testcaseTest模块的getStatusForReviewTest方法，参数是$case3, 'fail'  @wait
- 执行testcaseTest模块的getStatusForReviewTest方法，参数是$case4, 'fail'  @normal
- 执行testcaseTest模块的getStatusForReviewTest方法，参数是$case5, ''  @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

// 创建测试用例对象
$case1 = new stdClass();
$case1->status = 'wait';

$case2 = new stdClass();
$case2->status = 'normal';

$case3 = new stdClass();
$case3->status = 'wait';

$case4 = new stdClass();
$case4->status = 'normal';

$case5 = new stdClass();
$case5->status = 'fail';

r($testcaseTest->getStatusForReviewTest($case1, 'pass')) && p() && e('normal');
r($testcaseTest->getStatusForReviewTest($case2, 'pass')) && p() && e('normal');
r($testcaseTest->getStatusForReviewTest($case3, 'fail')) && p() && e('wait');
r($testcaseTest->getStatusForReviewTest($case4, 'fail')) && p() && e('normal');
r($testcaseTest->getStatusForReviewTest($case5, '')) && p() && e('fail');