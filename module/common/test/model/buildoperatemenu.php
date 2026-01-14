#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildOperateMenu();
timeout=0
cid=15648

- 步骤1：验证方法存在 @1
- 步骤2：验证方法为公共方法 @1
- 步骤3：验证第一个参数为object类型 @1
- 步骤4：验证第二个参数为string类型 @1
- 步骤5：验证参数数量 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->testBuildOperateMenuSignature(1)) && p() && e('1'); // 步骤1：验证方法存在
r($commonTest->testBuildOperateMenuSignature(2)) && p() && e('1'); // 步骤2：验证方法为公共方法
r($commonTest->testBuildOperateMenuSignature(3)) && p() && e('1'); // 步骤3：验证第一个参数为object类型
r($commonTest->testBuildOperateMenuSignature(4)) && p() && e('1'); // 步骤4：验证第二个参数为string类型
r($commonTest->testBuildOperateMenuSignature(5)) && p() && e('2'); // 步骤5：验证参数数量