#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getStatusForCreate();
timeout=0
cid=19096

- 步骤1：强制不评审且需要评审 @normal
- 步骤2：强制不评审且不需要评审 @normal
- 步骤3：不强制不评审且需要评审 @wait
- 步骤4：不强制不评审且不需要评审 @normal
- 步骤5：默认参数测试 @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

r($testcaseTest->getStatusForCreateTest(true, true)) && p() && e('normal');    // 步骤1：强制不评审且需要评审
r($testcaseTest->getStatusForCreateTest(true, false)) && p() && e('normal');   // 步骤2：强制不评审且不需要评审
r($testcaseTest->getStatusForCreateTest(false, true)) && p() && e('wait');     // 步骤3：不强制不评审且需要评审
r($testcaseTest->getStatusForCreateTest(false, false)) && p() && e('normal');  // 步骤4：不强制不评审且不需要评审
r($testcaseTest->getStatusForCreateTest()) && p() && e('normal');              // 步骤5：默认参数测试