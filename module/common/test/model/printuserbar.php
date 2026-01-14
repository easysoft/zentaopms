#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 commonModel::printUserBar();
timeout=0
cid=15702

- 步骤1：验证方法存在 @1
- 步骤2：验证方法为静态方法 @1
- 步骤3：验证方法为公共方法 @1
- 步骤4：验证参数数量为0 @1
- 步骤5：验证方法可以被调用 @1

*/

$commonTest = new commonModelTest();

r($commonTest->printUserBarTest(1)) && p() && e('1'); // 步骤1：验证方法存在
r($commonTest->printUserBarTest(2)) && p() && e('1'); // 步骤2：验证方法为静态方法
r($commonTest->printUserBarTest(3)) && p() && e('1'); // 步骤3：验证方法为公共方法
r($commonTest->printUserBarTest(4)) && p() && e('1'); // 步骤4：验证参数数量为0
r($commonTest->printUserBarTest(5)) && p() && e('1'); // 步骤5：验证方法可以被调用