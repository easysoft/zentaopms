#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';
su('admin');

/**

title=测试 commonModel::printAboutBar();
timeout=0
cid=15688

- 步骤1：验证方法存在 @1
- 步骤2：验证方法为静态方法 @1
- 步骤3：验证方法为公共方法 @1
- 步骤4：验证参数数量为0 @1
- 步骤5：验证方法可以被调用 @1

*/

$commonTest = new commonTest();

r($commonTest->printAboutBarTest(1)) && p() && e('1'); // 步骤1：验证方法存在
r($commonTest->printAboutBarTest(2)) && p() && e('1'); // 步骤2：验证方法为静态方法
r($commonTest->printAboutBarTest(3)) && p() && e('1'); // 步骤3：验证方法为公共方法
r($commonTest->printAboutBarTest(4)) && p() && e('1'); // 步骤4：验证参数数量为0
r($commonTest->printAboutBarTest(5)) && p() && e('1'); // 步骤5：验证方法可以被调用