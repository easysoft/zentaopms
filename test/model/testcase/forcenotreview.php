#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->forceNotReview();
cid=1
pid=1

测试检查是否强制审核 >> 1

*/

$testcase = new testcaseTest();

r($testcase->forceNotReviewTest()) && p() && e('1'); // 测试检查是否强制审核