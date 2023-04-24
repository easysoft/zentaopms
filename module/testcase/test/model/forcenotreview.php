#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->forceNotReview();
cid=1
pid=1

测试检查是否强制审核 >> 1

*/

$testcase = new testcaseTest();

r($testcase->forceNotReviewTest()) && p() && e('1'); // 测试检查是否强制审核